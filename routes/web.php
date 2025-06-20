<?php

use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminProductVariantController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\AdminBannerController;
use App\Http\Controllers\Admin\Accounts\AdminManageController;
use App\Http\Controllers\Admin\Accounts\ClientManageController;
use App\Http\Controllers\Admin\Accounts\SellerManageController;
use App\Http\Controllers\Admin\AdminReviewController;
use App\Http\Controllers\Admin\AdminVoucherController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminLoginController;
use Illuminate\Support\Facades\Session;

// Trang chủ (kiểm tra session nếu người dùng đã đăng nhập)
Route::get('/', function () {
    if (Session::has('admin_user')) {
        return redirect()->route('admin.dashboard');
    } else {
        return redirect()->route('admin.login.show');
    }
});

Route::prefix('admin')->name('admin.')->group(function () {
    // Đăng nhập
    Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('login.show');
    Route::post('/login', [AdminLoginController::class, 'login'])->name('login');
    Route::post('/logout', [AdminLoginController::class, 'logout'])->name('logout');

    // Đăng ký
    Route::get('/register', [AdminLoginController::class, 'showRegisterForm'])->name('register.show');
    Route::post('/register', [AdminLoginController::class, 'register'])->name('register');

    // Quên mật khẩu
    Route::get('/password/reset', [AdminLoginController::class, 'showResetRequestForm'])->name('password.request');
    Route::post('/password/email', [AdminLoginController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/password/reset/{token}', [AdminLoginController::class, 'showResetForm'])->name('password.reset');
    Route::post('/password/reset', [AdminLoginController::class, 'reset'])->name('password.update');

    // Dashboard (kiểm tra session thay vì middleware)
    Route::get('/dashboard', function () {
        if (!Session::has('admin_user')) {
            return redirect()->route('admin.login.show')->withErrors(['error' => 'Please login as admin.']);
        }
        return view('admin.auth.dashboard');
    })->name('dashboard');
    
     // Dashboards
    Route::resource('dashboards', AdminDashboardController::class);

    // Categories
    Route::resource('categories', AdminCategoryController::class);

    // Products
    Route::resource('products', AdminProductController::class);

    // Product variants
    Route::get('/variants/{id}/product', [AdminProductVariantController::class, 'index'])->name('variants.index');
    Route::get('/variants/{id}/create', [AdminProductVariantController::class, 'create'])->name('variants.create');
    Route::post('/variants', [AdminProductVariantController::class, 'store'])->name('variants.store');
    Route::get('/variants/{id}/edit', [AdminProductVariantController::class, 'edit'])->name('variants.edit');
    Route::put('/variants/{id}', [AdminProductVariantController::class, 'update'])->name('variants.update');
    Route::delete('/variants/{id}', [AdminProductVariantController::class, 'destroy'])->name('variants.destroy');

    // Accounts
    Route::prefix('accounts')->name('accounts.')->group(function () {
        // Client Management
        Route::resource('client-manage', ClientManageController::class);

        // Seller Management
        Route::resource('seller-manage', SellerManageController::class);

        // Admin Management
        Route::resource('admin-manage', AdminManageController::class);
    });

    // Reviews
    Route::resource('reviews', AdminReviewController::class);

    // View Reviews
    Route::patch('/reviews/{id}/toggle', [AdminReviewController::class, 'toggle'])->name('reviews.toggle');

    Route::patch('/products/{id}/toggle', [AdminProductController::class, 'toggle'])->name('products.toggle');
    // Banners
    Route::resource('banners', AdminBannerController::class);
    Route::resource('orders', AdminOrderController::class);

    // Vouchers
    Route::resource('vouchers', AdminVoucherController::class);
});