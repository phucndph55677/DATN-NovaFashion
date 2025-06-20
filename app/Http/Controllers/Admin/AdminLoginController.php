<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\PasswordResetToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Mail\WelcomeEmail;
use App\Mail\PasswordChanged;  

class AdminLoginController extends Controller
{
    // Hiển thị trang đăng nhập
    public function showLoginForm()
    {
        // Kiểm tra nếu người dùng đã đăng nhập, nếu có thì chuyển hướng đến dashboard
        if (Session::has('admin_user')) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.auth.login');
    }

    // Đăng nhập
    public function login(Request $request)
    {
        // Validate input với thông báo lỗi tiếng Việt
        $request->validate([
            'email' => 'required|email|exists:users,email', // email không được để trống
            'password' => 'required|string|min:8', // mật khẩu không được để trống
        ], [
            'email.required' => 'Email là bắt buộc.', // Thông báo lỗi nếu email để trống
            'email.email' => 'Địa chỉ email không hợp lệ.', // Thông báo lỗi nếu email không hợp lệ
            'email.exists' => 'Email không tồn tại trong hệ thống.', // Thông báo nếu email không tồn tại trong cơ sở dữ liệu
            'password.required' => 'Mật khẩu là bắt buộc.', // Thông báo lỗi nếu mật khẩu để trống
            'password.string' => 'Mật khẩu phải là chuỗi ký tự.', // Thông báo nếu mật khẩu không phải là chuỗi ký tự
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.', // Thông báo nếu mật khẩu ít hơn 8 ký tự
        ]);

        $credentials = $request->only('email', 'password');

        // Kiểm tra nếu người dùng tồn tại và mật khẩu chính xác
        $user = User::where('email', $credentials['email'])->first();

        // Kiểm tra người dùng tồn tại, mật khẩu chính xác và role_id là admin
        if ($user && Hash::check($credentials['password'], $user->password) && $user->role_id == 1) {
            // Lưu thông tin admin vào session
            Session::put('admin_user', $user);
            return redirect()->route('admin.dashboard');
        }

        // Nếu đăng nhập thất bại
        return back()->withErrors(['error' => 'Email hoặc mật khẩu không chính xác hoặc bạn không phải là admin.']);
    }


    // Đăng ký
    public function showRegisterForm()
    {
        return view('admin.auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|regex:/^0[0-9]{9,10}$/|unique:users,phone|max:11',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Tạo người dùng mới
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role_id' => 1, // Quản trị viên
            'status' => 1,
        ]);

        // Gửi email chào mừng đến người dùng sau khi đăng ký thành công
        Mail::to($user->email)->send(new WelcomeEmail($user));

        // Đăng nhập admin ngay sau khi đăng ký thành công
        Session::put('admin_user', $user);
        return redirect()->route('admin.dashboard');
    }

    // Đăng xuất
    public function logout()
    {
        // Xóa thông tin admin khỏi session
        Session::forget('admin_user');
        return redirect()->route('admin.login.show');
    }

    // Hiển thị form yêu cầu reset mật khẩu
    public function showResetRequestForm()
    {
        return view('admin.auth.passwords.email');
    }

    // Gửi link reset mật khẩu qua email
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        // Kiểm tra nếu email tồn tại trong bảng users
        $email = $request->email;
        if (!User::where('email', $email)->exists()) {
            return back()->withErrors(['email' => 'Email không tồn tại trong hệ thống.']);
        }

        // Tạo token mới
        $token = Str::random(60);

        // Kiểm tra xem email đã có trong bảng password_reset_tokens chưa
        $existingToken = PasswordResetToken::where('email', $email)->first();

        if ($existingToken) {
            // Nếu email đã có trong bảng, xóa token cũ và tạo token mới
            $existingToken->delete();
        }

        // Lưu token mới vào bảng password_reset_tokens
        PasswordResetToken::create([
            'email' => $email,
            'token' => $token,
            'created_at' => Carbon::now(),
        ]);

        // Tạo link reset mật khẩu
        $resetLink = url('admin/password/reset/' . $token);

        // Gửi email chứa link reset mật khẩu
        try {
            Mail::to($email)->send(new \App\Mail\ResetPasswordMail($resetLink));
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Không thể gửi email, vui lòng thử lại sau.']);
        }

        return back()->with('status', 'Link đặt lại mật khẩu đã được gửi tới email của bạn!');
    }

    // Hiển thị form nhập mật khẩu mới
    public function showResetForm(Request $request, $token = null)
    {
        $resetToken = PasswordResetToken::where('token', $token)->first();

        if (!$resetToken || Carbon::parse($resetToken->created_at)->addMinutes(60)->isPast()) {
            return redirect()->route('admin.login.show')->withErrors(['error' => 'Link reset mật khẩu không hợp lệ hoặc đã hết hạn.']);
        }

        return view('admin.auth.passwords.reset', ['token' => $token]);
    }

    // Cập nhật mật khẩu mới cho người dùng
    public function reset(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
            'token' => 'required',
        ]);

        // Kiểm tra token trong bảng password_reset_tokens
        $resetToken = PasswordResetToken::where('token', $request->token)->first();

        if (!$resetToken) {
            return back()->withErrors(['error' => 'Token không hợp lệ.']);
        }

        if (Carbon::parse($resetToken->created_at)->addMinutes(60)->isPast()) {
            return back()->withErrors(['error' => 'Token đã hết hạn.']);
        }

        // Lấy người dùng qua email từ token
        $user = User::where('email', $resetToken->email)->first();
        if (!$user) {
            return back()->withErrors(['error' => 'Không tìm thấy tài khoản.']);
        }

        // Cập nhật mật khẩu mới
        $user->password = Hash::make($request->password);
        $user->save();

        // Gửi email thông báo thay đổi mật khẩu
        Mail::to($user->email)->send(new PasswordChanged($user));

        // Xóa token đã sử dụng
        $resetToken->delete();

        return redirect()->route('admin.login.show')->with('success', 'Mật khẩu đã được thay đổi thành công.');
    }
}
