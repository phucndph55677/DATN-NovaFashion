<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Mail\VerifyEmail;
use App\Mail\WelcomeEmail;
use App\Mail\RequestEmail;
use App\Mail\PasswordReset;
use Illuminate\Support\Facades\Hash;   // ✅ import Hash
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class AdminAuthController extends Controller
{
    // Admin = 1
    private function isAdmin(User $user): bool
    {
        if (isset($user->role_id) && is_numeric($user->role_id) && (int)$user->role_id === 1) return true;
        if (isset($user->role)    && is_numeric($user->role)    && (int)$user->role    === 1) return true;
        if (isset($user->role)    && is_string($user->role)     && strtolower(trim($user->role)) === 'admin') return true;
        return false;
    }

    // Form đăng nhập
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    // Đăng nhập — chỉ admin (admin = 1)
    public function login(Request $request)
    {
        // ✅ Validate tiếng Việt
        $data = $request->validate(
            [
                'email'    => 'required|email',
                'password' => 'required|string|min:8',
            ],
            [
                'email.required'    => 'Email là bắt buộc.',
                'email.email'       => 'Địa chỉ email không hợp lệ.',
                'password.required' => 'Mật khẩu là bắt buộc.',
                'password.string'   => 'Mật khẩu phải là chuỗi ký tự.',
                'password.min'      => 'Mật khẩu phải có ít nhất :min ký tự.',
            ]
        );

        // Tìm user theo email
        $user = User::where('email', $data['email'])->first();

        if (!$user) {
            return back()->withErrors(['error' => 'Tài khoản không tồn tại.'])
                         ->withInput($request->only('email'));
        }
        if (!$this->isAdmin($user)) {
            return back()->withErrors(['error' => 'Chỉ quản trị viên mới được đăng nhập.'])
                         ->withInput($request->only('email'));
        }

        // Nếu có cột is_verified -> yêu cầu xác minh
        if (array_key_exists('is_verified', $user->getAttributes()) || property_exists($user, 'is_verified')) {
            if (!(bool) $user->is_verified) {
                return back()->withErrors([
                    'error' => 'Tài khoản admin chưa xác minh email. Vui lòng kiểm tra hộp thư để xác nhận.'
                ])->withInput($request->only('email'));
            }
        }

        // Kiểm tra mật khẩu
        if (!Hash::check($data['password'], $user->password)) {
            return back()->withErrors(['error' => 'Email hoặc mật khẩu không đúng.'])
                         ->withInput($request->only('email'));
        }

        // Đăng nhập vào guard admin
        Auth::guard('admin')->login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->route('admin.dashboards.index');
    }

    // Form đăng ký
    public function showRegisterForm()
    {
        return view('admin.auth.register');
    }

    // Đăng ký — tạo admin = 1
    public function register(Request $request)
    {
        // ✅ Validate tiếng Việt
        $data = $request->validate(
            [
                'name'                  => 'required',
                'phone'                 => 'required|regex:/^0[0-9]{9,10}$/|unique:users,phone',
                'email'                 => 'required|email|unique:users,email',
                'password'              => 'required|min:8',
                'password_confirmation' => 'required|same:password',
            ],
            [
                'name.required'                   => 'Vui lòng nhập họ và tên.',
                'phone.required'                  => 'Vui lòng nhập số điện thoại.',
                'phone.regex'                     => 'Số điện thoại không hợp lệ.',
                'phone.unique'                    => 'Số điện thoại này đã được đăng ký.',
                'email.required'                  => 'Email là bắt buộc.',
                'email.email'                     => 'Địa chỉ email không hợp lệ.',
                'email.unique'                    => 'Email này đã được đăng ký.',
                'password.required'               => 'Mật khẩu là bắt buộc.',
                'password.min'                    => 'Mật khẩu phải có ít nhất :min ký tự.',
                'password_confirmation.required'  => 'Vui lòng xác nhận mật khẩu.',
                'password_confirmation.same'      => 'Mật khẩu xác nhận không khớp.',
            ]
        );

        unset($data['password_confirmation']);
        $data['password'] = Hash::make($data['password']);   // ✅ Hash đúng
        $data['role_id'] = 1;                                // ✅ admin = 1
        $data['verification_token'] = Str::random(64);

        $user = User::create($data);

        // Gửi email xác minh
        Mail::to($user->email)->send(new VerifyEmail($user));

        // ✅ Thông báo hiển thị ở trang login
        return redirect()->route('admin.login.show')
            ->with('success', 'Đăng ký thành công. Vui lòng kiểm tra email để xác minh.');
    }

    // Xác minh email
    public function verifyEmail($token)
    {
        $user = User::where('verification_token', $token)->first();

        if (!$user) {
            return redirect()->route('admin.login.show')->with('error', 'Liên kết xác minh không hợp lệ.');
        }

        $user->update([
            'is_verified'        => true,
            'verification_token' => null
        ]);

        Mail::to($user->email)->send(new WelcomeEmail($user));

        return redirect()->route('admin.login.show');
    }

    // Form yêu cầu reset mật khẩu
    public function showRequestForm()
    {
        return view('admin.auth.recovers.request');
    }

    // Gửi link reset mật khẩu
    public function request(Request $request)
    {
        // ✅ Validate tiếng Việt
        $data = $request->validate(
            ['email' => 'required|email'],
            [
                'email.required' => 'Vui lòng nhập email.',
                'email.email'    => 'Địa chỉ email không hợp lệ.',
            ]
        );

        $user = User::where('email', $data['email'])->first();
        if (!$user) {
            return back()->withErrors(['email' => 'Email không tồn tại trong hệ thống.']);
        }

        $token = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            ['token' => Hash::make($token), 'created_at' => now()] // ✅ Hash token
        );

        try {
            Mail::to($user->email)->send(new RequestEmail($user, $token));
            return back()->with('success', 'Đã gửi liên kết đặt lại mật khẩu đến email của bạn.');
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Lỗi gửi mail: ' . $e->getMessage()]);
        }
    }

    // Form nhập mật khẩu mới
    public function showResetForm(Request $request, $token)
    {
        $email = $request->query('email');

        $resetToken = DB::table('password_reset_tokens')->where('email', $email)->first();

        if (!$resetToken || time() > strtotime($resetToken->created_at . ' +1 minutes')) {
            return redirect()->route('admin.request.show')->withErrors(['token' => 'Liên kết đặt lại mật khẩu đã hết hạn hoặc không hợp lệ.']);
        }

        return view('admin.auth.passwords.reset', [
            'token' => $token,
            'email' => $email,
        ]);
    }

    // Đặt lại mật khẩu mới
    public function reset(Request $request)
    {
        // ✅ Validate tiếng Việt
        $data = $request->validate(
            [
                'email'                 => 'required|email',
                'token'                 => 'required',
                'password'              => 'required|min:8',
                'password_confirmation' => 'required|same:password',
            ],
            [
                'email.required'                 => 'Vui lòng nhập email.',
                'email.email'                    => 'Email không hợp lệ.',
                'token.required'                 => 'Token không hợp lệ.',
                'password.required'              => 'Vui lòng nhập mật khẩu.',
                'password.min'                   => 'Mật khẩu phải có ít nhất :min ký tự.',
                'password_confirmation.required' => 'Vui lòng xác nhận mật khẩu.',
                'password_confirmation.same'     => 'Mật khẩu xác nhận không khớp.',
            ]
        );

        $resetToken = DB::table('password_reset_tokens')->where('email', $data['email'])->first();

        if (!$resetToken || !Hash::check($data['token'], $resetToken->token)) {
            return back()->withErrors(['token' => 'Link đặt lại mật khẩu không hợp lệ hoặc đã hết hạn.']);
        }

        $user = User::where('email', $data['email'])->first();
        if (!$user) {
            return back()->withErrors(['email' => 'Email không tồn tại.']);
        }

        $user->password = Hash::make($data['password']);
        $user->save();

        Mail::to($user->email)->send(new PasswordReset($user));
        DB::table('password_reset_tokens')->where('email', $data['email'])->delete();

        return redirect()->route('admin.login.show');
    }

    // Đăng xuất
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login.show');
    }
}
