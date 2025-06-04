<?php

use App\Http\Controllers\Admin\AdminProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductVariantController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\Accounts\AdminManageController;
use App\Http\Controllers\Admin\Accounts\ClientManageController;
use App\Http\Controllers\Admin\Accounts\SellerManageController;

Route::get('/', function () {
    return view('layouts.app');
});

Route::prefix('admin')->name('admin.')->group(function () {
    // Categories
    Route::resource('categories', AdminCategoryController::class);
    Route::resource('products', AdminProductController::class);

    // Products
    // Route::resource('/products', AdminProductController::class);
    // // Product variants
    // Route::get('/variants/{id}/product', [ProductVariantController::class, 'index'])->name('variants.index');
    // Route::get('/variants/{id}/create', [ProductVariantController::class, 'create'])->name('variants.create');
    // Route::post('/variants', [ProductVariantController::class, 'store'])->name('variants.store');
    // Route::get('/variants/{id}/edit', [ProductVariantController::class, 'edit'])->name('variants.edit');
    // Route::put('/variants/{id}', [ProductVariantController::class, 'update'])->name('variants.update');
    // Route::delete('/variants/{id}', [ProductVariantController::class, 'destroy'])->name('variants.destroy');
    
    // Accounts
     Route::prefix('accounts')->name('accounts.')->group(function () {
        // Admin Management
        Route::resource('admin-manage', AdminManageController::class)->parameters([
            'admin-manage' => 'user' // Ánh xạ tham số route 'admin-manage' thành 'user' trong controller
        ]);
        // Ví dụ: admin.accounts.admin-manage.index, admin.accounts.admin-manage.create, ...

        // Client Management
        Route::resource('client-manage', ClientManageController::class)->parameters([
            'client-manage' => 'user' // Ánh xạ tham số route 'client-manage' thành 'user' trong controller
        ]);
        // // Ví dụ: admin.accounts.client-manage.index, admin.accounts.client-manage.create, ...

        // Seller Management
        Route::resource('seller-manage', SellerManageController::class)->parameters([
            'seller-manage' => 'user' // Ánh xạ tham số route 'seller-manage' thành 'user' trong controller
        ]);
        // Ví dụ: admin.accounts.seller-manage.index, admin.accounts.seller-manage.create, ...
    });
    // Comments

    // Banners
});