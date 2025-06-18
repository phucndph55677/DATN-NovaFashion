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

Route::get('/', function () {
    return view('layouts.app');
});

Route::prefix('admin')->name('admin.')->group(function () {
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