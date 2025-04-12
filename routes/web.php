<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MerchantController;

Route::get('/', function () {
    return view('welcome');
});

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
});
