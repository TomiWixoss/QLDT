<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

// Public homepage
Route::get('/', function () {
    return view('customer.home');
})->name('home');

// Guest routes (not logged in as customer)
Route::middleware('guest:customer')->group(function () {
    // Registration routes
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])
        ->name('register');
    Route::post('/register', [RegisterController::class, 'store']);

    // Login routes
    Route::get('/login', [LoginController::class, 'showLoginForm'])
        ->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// Customer authenticated routes
Route::middleware('auth:customer')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])
        ->name('logout');
});
