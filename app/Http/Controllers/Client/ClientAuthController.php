<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Admin\AdminAuthController;
use App\Models\User;
use App\Mail\Client\ClientVerifyEmail;
use App\Mail\Client\ClientWelcomeEmail;
use App\Mail\Client\ClientRequestEmail;
use App\Mail\Client\ClientPasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class ClientAuthController extends AdminAuthController
{
    // Điều hướng đếm form Đăng nhập
    public function showLoginForm()
    {
        return view('client.auth.login');
    }

    // Đăng nhập
    public function login(Request $request)
    {
        // Validate
        $data = $request->validate(
            [
                'email' => 'required|email',
                'password' => 'required|string|min:8',
            ],
            [
                'email.required' => 'Email là bắt buộc.',
                'email.email' => 'Địa chỉ email không hợp lệ.',
                'password.required' => 'Mật khẩu là bắt buộc.',
                'password.string' => 'Mật khẩu phải là chuỗi ký tự.',
                'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
            ]
        );

        // Tìm user theo email
        $user = User::where('email', $data['email'])->first();

        // Nếu user không tồn tại hoặc chưa xác minh email
        if (!$user || !$user->is_verified) {
            return back()->withErrors([
                'error' => 'Tài khoản chưa được xác minh email hoặc không tồn tại.',
            ])->withInput($request->only('email'));
        }

        // Đăng nhập nếu xác minh rồi
        if (Auth::attempt($data)) {
            $request->session()->regenerate();
            return redirect()->route('home');
        }

        // Sai mật khẩu
        return back()->withErrors([
            'error' => 'Email hoặc mật khẩu không đúng.',
        ])->withInput($request->only('email')); // giữ lại email người dùng đã nhập
    }

    // Điều hướng đếm form Đăng ký
    public function showRegisterForm()
    {
        return view('client.auth.register');
    }

    // Đăng ký
    public function register(Request $request)
    {
        // Validate 
        $data = $request->validate(
            [
                'name' => 'required',
                'phone' => 'required||regex:/^0[0-9]{9,10}$/|unique:users,phone',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:8',
                'password_confirmation' => 'required|same:password',
                'agree' => 'accepted',
            ], 
            [
                'name.required' => 'Vui lòng nhập họ và tên.',
                'phone.required' => 'Vui lòng nhập số điện thoại.',
                'phone.regex' => 'Số điện thoại không hợp lệ.',
                'phone.unique' => 'Số điện thoại này đã được đăng ký.',
                'email.required' => 'Email là bắt buộc.',
                'email.email' => 'Địa chỉ email không hợp lệ.',
                'email.unique' => 'Email này đã được đăng ký.',
                'password.required' => 'Mật khẩu là bắt buộc.',
                'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
                'password_confirmation.required' => 'Vui lòng xác nhận mật khẩu.',
                'password_confirmation.same' => 'Mật khẩu xác nhận không khớp.',
                'agree.accepted' => 'Bạn phải đồng ý với điều khoản của NovaFashion.',
            ]
        );

        // Bỏ password_confirmation khỏi mảng và hash password
        unset($data['password_confirmation']);
        $data['password'] = bcrypt($data['password']);

        // Gán role_id mặc định là 1 (admin)
        $data['role_id'] = 3;

        $data['verification_token'] = Str::random(64);

        $user = User::query()->create($data);

        // Gửi email xác minh
        Mail::to($user->email)->send(new ClientVerifyEmail($user));

        return redirect()->route('login.show')->with('success', 'Đăng ký thành công. Vui lòng kiểm tra email để xác minh.');
    }

    // Xác thực Email
    public function verifyEmail($token)
    {
        $user = User::where('verification_token', $token)->first();

        if (!$user) {
            return redirect()->route('login.show')->with('error', 'Liên kết xác minh không hợp lệ.');
        }

        $user->update([
            'is_verified' => true,
            'verification_token' => null
        ]);

        Mail::to($user->email)->send(new ClientWelcomeEmail($user));

        return redirect()->route('login.show');
    }

    // Hiển thị form yêu cầu reset mật khẩu
    public function showRequestForm()
    {
        return view('client.auth.passwords.request');
    }

    // Gửi link reset mật khẩu qua email
    public function request(Request $request)
    {
        // Validate 
        $data = $request->validate(
            [
                'email' => 'required|email', 
            ],
            [
                'email.required' => 'Vui lòng nhập email.',
                'email.email' => 'Địa chỉ email không hợp lệ.',
            ]
        );

        // Tìm user theo email
        $user = User::where('email', $data['email'])->first();
        if (!$user) {
            return back()->withErrors(['email' => 'Email không tồn tại trong hệ thống.']);
        }

        // Tạo token mới
        $token = Str::random(64);

        // Lưu token (hash để bảo mật)
        DB::table('password_reset_tokens')->updateOrInsert(
            [
                'email' => $user->email,
            ],
            [
                'token' => Hash::make($token),
                'created_at' => now(),
            ]
        );

        // Gửi email chứa link reset mật khẩu
        try {
            Mail::to($user->email)->send(new ClientRequestEmail($user, $token));
            return back()->with('success', 'Đã gửi liên kết đặt lại mật khẩu đến email của bạn.');
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Lỗi gửi mail: ' . $e->getMessage()]);
        }
    }

    // Hiển thị form nhập mật khẩu mới
    public function showResetForm(Request $request, $token)
    {
        $email = $request->query('email');

        $resetToken = DB::table('password_reset_tokens')->where('email', $email)->first();

        // Kiểm tra token không tồn tại hoặc đã hết hạn
        if (!$resetToken || time() > strtotime($resetToken->created_at . ' +1 minutes')) {
            return redirect()->route('admin.request.show')->withErrors(['token' => 'Liên kết đặt lại mật khẩu đã hết hạn hoặc không hợp lệ.']);
        }

        return view('client.auth.passwords.reset', [
            'token' => $token,
            'email' => $email,
        ]);
    }

    // Cập nhật mật khẩu mới cho người dùng
    public function reset(Request $request)
    {
        $data = $request->validate(
            [
                'email' => 'required|email',
                'token' => 'required',
                'password' => 'required|min:8',
                'password_confirmation' => 'required|same:password',
            ],
            [
                'email.required' => 'Vui lòng nhập email.',
                'email.email' => 'Email không hợp lệ.',
                'token.required' => 'Token không hợp lệ.',
                'password.required' => 'Vui lòng nhập mật khẩu.',
                'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
                'password_confirmation.required' => 'Vui lòng xác nhận mật khẩu.',
                'password_confirmation.same' => 'Mật khẩu xác nhận không khớp.',
            ]
        );

        // Lấy resetToken trong bảng password_reset_tokens
        $resetToken = DB::table('password_reset_tokens')->where('email', $data['email'])->first();

        if (!$resetToken || !Hash::check($data['token'], $resetToken->token)) {
            return back()->withErrors(['token' => 'Link đặt lại mật khẩu không hợp lệ hoặc đã hết hạn.']);
        }

        // Tìm user theo email
        $user = User::where('email', $data['email'])->first();
        if (!$user) {
            return back()->withErrors(['email' => 'Email không tồn tại.']);
        }

        // Cập nhật mật khẩu mới
        $user->password = Hash::make($data['password']);
        $user->save();

        // Gửi email xác nhận
        Mail::to($user->email)->send(new ClientPasswordReset($user));  

        // Xóa token sau khi dùng
        DB::table('password_reset_tokens')->where('email', $data['email'])->delete();

        // Chuyển hướng về form đăng nhập 
        return redirect()->route('login.show');
    }

    // Đăng xuất
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login.show');
    }
}
