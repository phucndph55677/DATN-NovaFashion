<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Admin\AdminAuthController;
use App\Models\User;
use App\Mail\ClientVerifyEmail;
use App\Mail\ClientWelcomeEmail;
use App\Mail\ClientRequestEmail;
use App\Mail\ClientPasswordReset;
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

    public function showRegisterForm()
    {
        return view('client.auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate(
            [
                'name' => 'required',
                'phone' => 'required|regex:/^0[0-9]{9,10}$/|unique:users,phone',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:8',
                'password_confirmation' => 'required|same:password',
                'address' => 'required|string|max:255',
                'captcha' => 'required|captcha',
                'customer_agree' => 'accepted',
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
                'address.required' => 'Vui lòng nhập địa chỉ.',
                'address.string' => 'Địa chỉ không hợp lệ.',
                'address.max' => 'Địa chỉ không được vượt quá 255 ký tự.',
                'captcha.required' => 'Vui lòng nhập mã xác thực.',
                'captcha.captcha' => 'Mã xác thực không đúng.',
                'customer_agree.accepted' => 'Bạn phải đồng ý với điều khoản của IVY.',
            ]
        );

        unset($data['password_confirmation']);
        $data['password'] = bcrypt($data['password']);
        $data['role_id'] = 3;
        $data['verification_token'] = Str::random(64);

        $user = User::query()->create($data);

        try {
            Mail::to($user->email)->send(new ClientVerifyEmail($user));
            return redirect()->route('login')->with('success', 'Đăng ký thành công. Vui lòng kiểm tra email để xác minh.');
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['email' => 'Lỗi gửi email xác minh: ' . $e->getMessage()]);
        }
    }

    public function verifyEmail($token)
    {
        $user = User::where('verification_token', $token)->first();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Liên kết xác minh không hợp lệ.');
        }

        $user->update([
            'is_verified' => true,
            'verification_token' => null
        ]);

        try {
            Mail::to($user->email)->send(new ClientWelcomeEmail($user));
        } catch (\Exception $e) {
            // Không redirect lỗi, chỉ ghi log nếu cần
        }

        return redirect()->route('login')->with('success', 'Xác minh email thành công. Vui lòng đăng nhập.');
    }

    public function showRequestForm()
    {
        return view('client.auth.passwords.email');
    }

    public function request(Request $request)
    {
        $data = $request->validate(
            [
                'email' => 'required|email',
                'captcha' => 'required|captcha',
            ],
            [
                'email.required' => 'Vui lòng nhập email.',
                'email.email' => 'Địa chỉ email không hợp lệ.',
                'captcha.required' => 'Vui lòng nhập mã xác thực.',
                'captcha.captcha' => 'Mã xác thực không đúng.',
            ]
        );

        $user = User::where('email', $data['email'])->first();
        if (!$user) {
            return back()->withErrors(['email' => 'Email không tồn tại trong hệ thống.']);
        }

        $token = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            [
                'token' => Hash::make($token),
                'created_at' => now(),
            ]
        );

        try {
            Mail::to($user->email)->send(new ClientRequestEmail($user, $token));
            return back()->with('success', 'Đã gửi liên kết đặt lại mật khẩu đến email của bạn.');
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Lỗi gửi mail: ' . $e->getMessage()]);
        }
    }

    public function showResetForm(Request $request, $token)
    {
        $email = $request->query('email');

        $resetToken = DB::table('password_reset_tokens')->where('email', $email)->first();

        if (!$resetToken || time() > strtotime($resetToken->created_at . ' +60 minutes')) {
            return redirect()->route('password.request')->withErrors(['error' => 'Liên kết đặt lại mật khẩu đã hết hạn hoặc không hợp lệ.']);
        }

        return view('client.auth.passwords.reset', [
            'token' => $token,
            'email' => $email,
        ]);
    }

    public function reset(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'token' => 'required',
            'password' => 'required|min:8',
            'password_confirmation' => 'required|same:password',
        ], [
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không hợp lệ.',
            'token.required' => 'Token không hợp lệ.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'password_confirmation.required' => 'Vui lòng xác nhận mật khẩu.',
            'password_confirmation.same' => 'Mật khẩu xác nhận không khớp.',
        ]);

        $resetToken = DB::table('password_reset_tokens')->where('email', $data['email'])->first();

        if (!$resetToken || !Hash::check($data['token'], $resetToken->token)) {
            return back()->withErrors(['error' => 'Link đặt lại mật khẩu không hợp lệ hoặc đã hết hạn.']);
        }

        $user = User::where('email', $data['email'])->first();
        if (!$user) {
            return back()->withErrors(['email' => 'Email không tồn tại.']);
        }

        $user->password = Hash::make($data['password']);
        $user->save();

        try {
            Mail::to($user->email)->send(new ClientPasswordReset($user));
        } catch (\Exception $e) {
            // Không redirect lỗi, chỉ ghi log nếu cần
        }

        DB::table('password_reset_tokens')->where('email', $data['email'])->delete();

        return redirect()->route('login');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
