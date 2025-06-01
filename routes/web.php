<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('layouts.app');
});

Route::prefix('admin')->name('admin.')->group(function () {
    // Categories
    Route::resource('/categories', AdminCategoryController::class);

    // Products
    Route::resource('/products', AdminProductController::class);
    // Product variants
    Route::get('/variants/{id}/product', [ProductVariantController::class, 'index'])->name('variants.index');
    Route::get('/variants/{id}/create', [ProductVariantController::class, 'create'])->name('variants.create');
    Route::post('/variants', [ProductVariantController::class, 'store'])->name('variants.store');
    Route::get('/variants/{id}/edit', [ProductVariantController::class, 'edit'])->name('variants.edit');
    Route::put('/variants/{id}', [ProductVariantController::class, 'update'])->name('variants.update');
    Route::delete('/variants/{id}', [ProductVariantController::class, 'destroy'])->name('variants.destroy');
    
    // Accounts

    // Comments

    // Banners
});