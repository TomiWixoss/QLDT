<?php

use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\SetPasswordController;
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

    // Google OAuth routes
    Route::get('/auth/google', [GoogleAuthController::class, 'redirectToGoogle'])
        ->name('auth.google');
    Route::get('/auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback'])
        ->name('auth.google.callback');
});

// Customer authenticated routes
Route::middleware('auth:customer')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])
        ->name('logout');

    // Set password routes (for Google-registered users)
    Route::get('/password/set', [SetPasswordController::class, 'showSetPasswordForm'])
        ->name('password.set');
    Route::post('/password/set', [SetPasswordController::class, 'setPassword']);
});
