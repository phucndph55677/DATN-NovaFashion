<?php

use App\Http\Controllers\Admin\AdminProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminProductVariantController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\AdminBannerController;
use App\Http\Controllers\BannerController;

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
    // Route::put('/variants/{id}', [AdminProductVariantController::class, 'update'])->name('variants.update');
    // Route::delete('/variants/{id}', [AdminProductVariantController::class, 'destroy'])->name('variants.destroy');
    
    // Accounts

    // Comments

    // Banners
    Route::resource('banners', AdminBannerController::class);

});
