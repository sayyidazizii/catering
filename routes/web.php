<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MerchantController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth', 'role:merchant'])->prefix('merchant')->name('merchant.')->group(function () {
    Route::get('/profile', [MerchantController::class, 'editProfile'])->name('profile.edit');
    Route::post('/profile', [MerchantController::class, 'updateProfile'])->name('profile.update');
});
