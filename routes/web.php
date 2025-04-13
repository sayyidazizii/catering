<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\MerchantController;
use App\Http\Controllers\Auth\RegisterController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/register-customer', [RegisterController::class, 'showCustomerRegisterForm'])->name('register.customer.form');
Route::post('/register-customer', [RegisterController::class, 'registerCustomer'])->name('register.customer');

Route::get('/register-merchant', [RegisterController::class, 'showMerchantRegisterForm'])->name('register.merchant.form');
Route::post('/register-merchant', [RegisterController::class, 'registerMerchant'])->name('register.merchant');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [UserController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [UserController::class, 'update'])->name('profile.update');
});

Route::middleware(['auth', 'role:merchant'])->prefix('merchant')->name('merchant.')->group(function () {
    Route::get('/profile', [MerchantController::class, 'editProfile'])->name('profile.edit');
    Route::post('/profile', [MerchantController::class, 'updateProfile'])->name('profile.update');

    Route::get('menus', [MenuController::class, 'index'])->name('menu.index');
    Route::get('menus/create', [MenuController::class, 'create'])->name('menu.create');
    Route::post('menus', [MenuController::class, 'store'])->name('menu.store');
    Route::get('menus/{id}/edit', [MenuController::class, 'edit'])->name('menu.edit');
    Route::put('menus/{id}', [MenuController::class, 'update'])->name('menu.update');
    Route::delete('menus/{id}', [MenuController::class, 'destroy'])->name('menu.destroy');

    Route::get('/merchant/orders', [MerchantController::class, 'order'])->name('orders.list');

    Route::put('/orders/{orderId}/update', [MerchantController::class, 'updateStatus'])->name('order.update');
    
    Route::get('/orders/{orderId}/invoice', [MerchantController::class, 'printInvoice'])->name('order.invoice');

});

Route::middleware(['auth', 'role:customer'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/menu', [OrderController::class, 'create'])->name('order.create');
    // Route::post('/order', [OrderController::class, 'store'])->name('order.store');
    Route::post('/customer/order', [OrderController::class, 'storeOrder'])->name('order.store');
    Route::delete('/order/remove/{menuId}', [OrderController::class, 'removeFromCart'])->name('order.remove');
    Route::get('/customer/checkout', [OrderController::class, 'checkout'])->name('order.checkout');
    Route::post('/customer/checkout', [OrderController::class, 'processCheckout'])->name('checkout.process');

    Route::get('/order/history', [OrderController::class, 'purchaseHistory'])->name('order.history');
    Route::get('/order/{orderId}/detail', [OrderController::class, 'orderDetail'])->name('order.detail');
    Route::get('/customer/order/invoice/{id}', [OrderController::class, 'invoice'])->name('order.invoice');


});

