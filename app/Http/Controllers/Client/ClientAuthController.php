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

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
