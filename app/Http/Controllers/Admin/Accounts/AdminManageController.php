<?php

namespace App\Http\Controllers\Admin\Accounts;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;

class AdminManageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $admin = Auth::user();

        return view('admin.accounts.adminManage.index', compact('admin'));
    }

    private function resolveGuard(): string
    {
        if (Auth::guard('admin')->check()) return 'admin';
        if (Auth::guard('web')->check())   return 'web';
        return config('auth.defaults.guard');
    }

    /**
     * Kiểm tra user có phải admin (role id = 1).
     * Hỗ trợ: users.role_id, users.role (int) hoặc các pivot phổ biến.
     */
    private function userIsAdmin($user): bool
    {
        // 1) users.role_id
        if (isset($user->role_id) && is_numeric($user->role_id)) {
            return (int) $user->role_id === 1;
        }

        // 2) users.role (tránh trùng tên với quan hệ)
        if (method_exists($user, 'getAttribute')) {
            $rawRole = $user->getAttribute('role');
            if (is_numeric($rawRole)) {
                return (int) $rawRole === 1;
            }
        }

        // 3) Pivot role_user(user_id, role_id)
        if (Schema::hasTable('role_user')) {
            $exists = DB::table('role_user')
                ->where('user_id', $user->id)
                ->where('role_id', 1)
                ->exists();
            if ($exists) return true;
        }

        // 4) Spatie: model_has_roles(role_id, model_type, model_id)
        if (Schema::hasTable('model_has_roles')) {
            $exists = DB::table('model_has_roles')
                ->where('role_id', 1)
                ->where('model_type', User::class)
                ->where('model_id', $user->id)
                ->exists();
            if ($exists) return true;
        }

        // 5) user_roles(user_id, role_id)
        if (Schema::hasTable('user_roles')) {
            $exists = DB::table('user_roles')
                ->where('user_id', $user->id)
                ->where('role_id', 1)
                ->exists();
            if ($exists) return true;
        }

        return false;
    }

    public function edit(Request $request)
    {
        $guard = $this->resolveGuard();
        $user  = Auth::guard($guard)->user();

        if (!$user) {
            return back()->with('error', 'Chưa đăng nhập.');
        }
        if (!$this->userIsAdmin($user)) {
            return back()->with('error', 'Bạn không có quyền truy cập trang này.');
        }

        return view('admin.profile.edit', ['admin' => $user]);
    }

    public function updatePassword(Request $request)
    {
        // Validate: 8–32 ký tự, khác mật khẩu hiện tại, xác nhận khớp
        $data = $request->validate(
            [
                'current_password'          => ['required','string'],
                'new_password'              => ['required','string','min:8','max:32','different:current_password'],
                'new_password_confirmation' => ['required','same:new_password'],
            ],
            [
                'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại.',
                'new_password.required'     => 'Vui lòng nhập mật khẩu mới.',
                'new_password.min'          => 'Mật khẩu mới phải có ít nhất :min ký tự.',
                'new_password.max'          => 'Mật khẩu mới không được dài quá :max ký tự.',
                'new_password.different'    => 'Mật khẩu mới phải khác mật khẩu hiện tại.',
                'new_password_confirmation.required' => 'Vui lòng nhập lại mật khẩu mới.',
                'new_password_confirmation.same'     => 'Xác nhận mật khẩu không khớp.',
            ]
        );

        $guard = $this->resolveGuard();
        $user  = Auth::guard($guard)->user();
        if (!$user) {
            return back()->with('error', 'Chưa đăng nhập.');
        }
        if (!$this->userIsAdmin($user)) {
            return back()->with('error', 'Bạn không có quyền thực hiện hành động này.');
        }

        // Kiểm tra mật khẩu hiện tại
        if (! Hash::check($data['current_password'], $user->password)) {
            return back()->with('error', 'Mật khẩu hiện tại không đúng.')->withInput();
        }

        // Cập nhật mật khẩu (giữ session, KHÔNG logout, KHÔNG chuyển trang)
        $updated = DB::table('users')
            ->where('id', $user->id)
            ->update([
                'password'   => Hash::make($data['new_password']),
                'updated_at' => now(),
            ]);

        if ($updated) {
            return back()->with('status', 'Đã đổi mật khẩu thành công.');
        }

        return back()->with('error', 'Không thể đổi mật khẩu, vui lòng thử lại.');
    }
}
