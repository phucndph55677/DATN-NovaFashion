<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminProductVariantController;
use App\Http\Controllers\Admin\AdminBannerController;
use App\Http\Controllers\Admin\AdminReviewController;
use App\Http\Controllers\Admin\AdminVoucherController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\Accounts\AdminManageController;
use App\Http\Controllers\Admin\Accounts\ClientManageController;

// ROUTE ADMIN
Route::prefix('admin')->name('admin.')->group(function () {
    // Các route không cần đăng nhập
    Route::middleware('guest:admin')->group(function () {
        // Đăng nhập
        Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login.show');
        Route::post('/login', [AdminAuthController::class, 'login'])->name('login');

        // Đăng ký
        Route::get('/register', [AdminAuthController::class, 'showRegisterForm'])->name('register.show');
        Route::post('/register', [AdminAuthController::class, 'register'])->name('register');

        // Xác minh email
        Route::get('/verify-email/{token}', [AdminAuthController::class, 'verifyEmail'])->name('verify.email');

        // Quên mật khẩu
        Route::get('recovers/request', [AdminAuthController::class, 'showRequestForm'])->name('request.show');
        Route::post('recovers/request', [AdminAuthController::class, 'request'])->name('request');
        Route::get('/password/reset/{token}', [AdminAuthController::class, 'showResetForm'])->name('password.reset');
        Route::post('/password/reset', [AdminAuthController::class, 'reset'])->name('password.update');
    });

    // Các route cần đăng nhập
    Route::middleware('admin.auth')->group(function () {
        // Đăng xuất
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

        // Dashboards
        Route::resource('dashboards', AdminDashboardController::class);

        // Categories
        Route::resource('categories', AdminCategoryController::class);

        // Products
        Route::resource('products', AdminProductController::class);

        // Album ảnh
        Route::put('products/{id}/album', [AdminProductController::class, 'updateAlbum'])->name('products.updateAlbum');

        // Banners
        Route::resource('banners', AdminBannerController::class);

        // Order
        Route::resource('orders', AdminOrderController::class);
        Route::put('/orders/{id}/payment-status', [AdminOrderController::class, 'updatePaymentStatus'])->name('orders.updatePaymentStatus');
        Route::put('/orders/{id}/order-status', [AdminOrderController::class, 'updateOrderStatus'])->name('orders.updateOrderStatus');

        // Order Return
        Route::get('order-return', [AdminOrderController::class, 'indexReturn'])->name('indexReturn');
        Route::get('show-return/{id}', [AdminOrderController::class, 'showReturn'])->name('showReturn');
        Route::put('orders/{order}/return', [AdminOrderController::class, 'handleReturn'])->name('handle.return');
        
        // Order Refund
        Route::get('order-refund', [AdminOrderController::class, 'indexRefund'])->name('indexRefund');
        Route::get('show-refund/{id}', [AdminOrderController::class, 'showRefund'])->name('showRefund');
        Route::put('orders/{order}/refund', [AdminOrderController::class, 'handleRefund'])->name('handle.refund');

        // Vouchers
        Route::resource('vouchers', AdminVoucherController::class);

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

            // Admin Management
            Route::resource('admin-manage', AdminManageController::class);
        });

        // Hồ sơ cá nhân + Đổi mật khẩu (admin đã đăng nhập)
            Route::get('/profile', [AdminManageController::class, 'edit'])->name('profile.edit');

            Route::put('/profile/password', [AdminManageController::class, 'updatePassword'])
                ->middleware('throttle:6,1') // hạn chế thử sai liên tục
                ->name('profile.password.update');

        // Reviews
        Route::resource('reviews', AdminReviewController::class);

        // View Reviews
        Route::patch('/reviews/{id}/toggle', [AdminReviewController::class, 'toggle'])->name('reviews.toggle');

        Route::patch('/products/{id}/toggle', [AdminProductController::class, 'toggle'])->name('products.toggle');
    });
});
