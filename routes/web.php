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
use App\Http\Controllers\Admin\Accounts\SellerManageController;

use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\ClientAuthController;
use App\Http\Controllers\Client\ClientProductController;
use App\Http\Controllers\Client\ClientCartController;
use App\Http\Controllers\Client\ClientFavoriteController;


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

        // Banners
        Route::resource('banners', AdminBannerController::class);

        // Order
        Route::resource('orders', AdminOrderController::class);

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
    });
});


// ROUTE CLIENT
// Home
Route::get('/', [HomeController::class, 'index'])->name('home');

// Product show
Route::get('/san-pham/{id}', [ClientProductController::class, 'show'])->name('products.show');

// Đăng nhập
Route::get('/login', [ClientAuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [ClientAuthController::class, 'login'])->name('login.post');
// Route cho đăng xuất client
Route::post('/logout', [ClientAuthController::class, 'logout'])->name('logout');

// Carts
Route::get('/carts', [ClientCartController::class, 'index'])->name('carts.index');
Route::post('/carts/add', [ClientCartController::class, 'addToCart'])->name('carts.add');
Route::post('/carts/buy', [ClientCartController::class, 'buyNow'])->name('carts.buy');
Route::post('/carts/{id}', [ClientCartController::class, 'destroy'])->name('carts.delete');

// Product Farovite
Route::get('/yeu-thich', [ClientFavoriteController::class, 'index'])->name('favorites.index');
Route::post('/yeu-thich/toggle', [ClientFavoriteController::class, 'toggleFavorite'])->name('favorites.toggle');
