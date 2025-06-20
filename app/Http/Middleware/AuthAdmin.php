<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AuthAdmin
{
    public function handle(Request $request, Closure $next)
    {
        // Kiểm tra nếu người dùng đã đăng nhập và có thông tin admin trong session
        $user = Session::get('admin_user');

        // Nếu không có thông tin admin, chuyển hướng về trang đăng nhập
        if (!$user) {
            return redirect()->route('admin.login.show')->withErrors(['error' => 'Please login as admin.']);
        }

        // Kiểm tra role_id nếu cần thiết (giả sử 3 là admin)
        if ($user && $user->role_id != 3) {
            Session::forget('admin_user');  // Đăng xuất nếu không phải admin
            return redirect()->route('admin.login.show')->withErrors(['error' => 'Unauthorized access.']);
        }

        // Nếu người dùng là admin, tiếp tục xử lý request
        return $next($request);
    }
}

