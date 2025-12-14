<?php

use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

// Public homepage
Route::get('/', function () {
    return view('customer.home');
})->name('home');

// Guest routes (not logged in as customer)
Route::middleware('guest:customer')->group(function () {
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])
        ->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
});
