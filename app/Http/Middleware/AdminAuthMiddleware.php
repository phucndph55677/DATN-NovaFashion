<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Kiểm tra admin đã đăng nhập qua admin route
        if (Auth::guard('admin')->check()) {
            return $next($request);
        }
        
        // Kiểm tra admin đã đăng nhập qua client route với role_id = 1
        if (Auth::check() && Auth::user()->role_id == 1) {
            return $next($request);
        }
        
        // Nếu không phải admin, redirect về trang đăng nhập admin
        return redirect()->route('admin.login.show')->withErrors(['error' => 'Bạn cần đăng nhập admin']);
    }
}