<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\ClientAuthController;
use App\Http\Controllers\Client\ClientCategoryController;
use App\Http\Controllers\Client\ClientProductController;
use App\Http\Controllers\Client\ClientCartController;
use App\Http\Controllers\Client\ClientCheckoutController;
use App\Http\Controllers\Client\ClientPaymentController;
use App\Http\Controllers\Client\ClientAccountController;
use App\Http\Controllers\Client\ClientChatBotController;
use App\Http\Controllers\Client\ClientChatController;


// ROUTE CLIENT
// Đăng nhập
Route::get('/login', [ClientAuthController::class, 'showLoginForm'])->name('login.show');
Route::post('/login', [ClientAuthController::class, 'login'])->name('login');

// Đăng xuất
Route::post('/logout', [ClientAuthController::class, 'logout'])->name('logout');

// Đăng ký
Route::get('/register', [ClientAuthController::class, 'showRegisterForm'])->name('register.show');
Route::post('/register', [ClientAuthController::class, 'register'])->name('register');

// Xác minh email
Route::get('/verify-email/{token}', [ClientAuthController::class, 'verifyEmail'])->name('verify.email');

// Quên mật khẩu
Route::get('/password/reset', [ClientAuthController::class, 'showRequestForm'])->name('password.show');
Route::post('/password/reset', [ClientAuthController::class, 'request'])->name('password.email');
Route::get('/password/reset/{token}', [ClientAuthController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/update', [ClientAuthController::class, 'reset'])->name('password.update');

// Home
Route::get('/', [HomeController::class, 'index'])->name('home');

// Category
Route::get('/categories/{slug}/{subslug?}/{childslug?}', [ClientCategoryController::class, 'index'])->name('categories.index');

// Product show
Route::get('/san-pham/{id}', [ClientProductController::class, 'show'])->name('products.show');

// Carts
Route::get('/carts', [ClientCartController::class, 'index'])->name('carts.index');
Route::patch('/carts/update-quantity', [ClientCartController::class, 'updateQuantity'])->name('carts.updateQuantity');
Route::post('/carts/add', [ClientCartController::class, 'addToCart'])->name('carts.add');
Route::post('/carts/buy', [ClientCartController::class, 'buyNow'])->name('carts.buy');
Route::post('/carts/{id}', [ClientCartController::class, 'destroy'])->name('carts.delete');

// Mini Cart
Route::get('/mini-cart', [ClientCartController::class, 'miniCart'])->name('miniCart');

// ChatBot API
Route::get('/chatbot/messages', [ClientChatBotController::class, 'index'])->name('chatbot.index');
Route::post('/chatbot/messages', [ClientChatBotController::class, 'store'])->name('chatbot.message');

// Chat
Route::get('/chats', [ClientChatController::class, 'index'])->name('chats.index');
Route::post('/chats/send', [ClientChatController::class, 'store'])->name('chats.store');
Route::get('/chats/{chatId}/messages', [ClientChatController::class, 'getMessages'])->name('chats.messages');

// Checkouts
Route::get('/checkouts', [ClientCheckoutController::class, 'index'])->name('checkouts.index');
Route::post('/checkouts', [ClientCheckoutController::class, 'store'])->name('checkouts.store');
Route::get('checkouts/success', [ClientCheckoutController::class, 'success'])->name('checkouts.success');

// Vouchers
Route::post('/vouchers/apply', [ClientCheckoutController::class, 'applyVoucher'])->name('vouchers.apply');
Route::post('/vouchers/clear', [ClientCheckoutController::class, 'clearVoucher'])->name('vouchers.clear');

// Payments
Route::get('/payments/momo', [ClientPaymentController::class, 'momo'])->name('payments.momo');
Route::match(['get', 'post'], '/payments/momo/callback', [ClientPaymentController::class, 'momoCallback'])->name('payments.momo.callback');

// Account
Route::prefix('account')->name('account.')->group(function () {
    // Search
    Route::get('/search', [ClientAccountController::class, 'search'])->name('products.search');

    // Info
    Route::get('/info', [ClientAccountController::class, 'info'])->name('info');

    // Cập nhật thông tin tài khoản
    Route::put('/info', [ClientAccountController::class, 'update'])->name('update');

    // Đổi mật khẩu
    Route::post('/password', [ClientAccountController::class, 'updatePassword'])->name('password.update');
    
    // Order
    Route::get('/orders', [ClientAccountController::class, 'index'])->name('orders.index');
    Route::get('/orders/track/{id}', [ClientAccountController::class, 'track'])->name('orders.track');
    Route::get('orders/show/{id}', [ClientAccountController::class, 'show'])->name('orders.show');
    Route::post('/orders/{id}/cancel', [ClientAccountController::class, 'cancel'])->name('orders.cancel');
    Route::post('/orders/{id}/return', [ClientAccountController::class, 'return'])->name('orders.return');

    // Reviews
    Route::get('/reviews', [ClientAccountController::class, 'review'])->name('reviews.index');
    Route::post('/reviews', [ClientAccountController::class, 'store'])->name('reviews.store');

    // Favorites
    Route::get('/favorites', [ClientAccountController::class, 'favorite'])->name('favorites.index');
    Route::post('/favorites/toggle', [ClientAccountController::class, 'toggleFavorite'])->name('favorites.toggle');
});
