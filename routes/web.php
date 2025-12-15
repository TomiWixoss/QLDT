<?php

use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PosController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\SetPasswordController;
use App\Http\Controllers\Customer\ProfileController;
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

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
});

// ============================================
// ADMIN ROUTES
// ============================================

// Admin Guest Routes (not logged in)
Route::prefix('admin')->middleware('guest')->group(function () {
    Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminLoginController::class, 'login'])->name('admin.login.submit');
});

// Admin Authenticated Routes
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::post('/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Products - All staff can view
    Route::middleware('role:Admin,Manager,Sales,Warehouse')->group(function () {
        Route::get('/products', [ProductController::class, 'index'])->name('admin.products.index');
    });

    // Orders - Admin, Manager, Sales
    Route::middleware('role:Admin,Manager,Sales')->group(function () {
        Route::get('/orders', [OrderController::class, 'index'])->name('admin.orders.index');
    });

    // Inventory - Admin, Manager, Warehouse
    Route::middleware('role:Admin,Manager,Warehouse')->group(function () {
        Route::get('/inventory', [InventoryController::class, 'index'])->name('admin.inventory.index');
    });

    // Customers - Admin, Manager, Sales
    Route::middleware('role:Admin,Manager,Sales')->group(function () {
        Route::get('/customers', [CustomerController::class, 'index'])->name('admin.customers.index');
    });

    // POS - Admin, Manager, Sales
    Route::middleware('role:Admin,Manager,Sales')->group(function () {
        Route::get('/pos', [PosController::class, 'index'])->name('admin.pos.index');
    });

    // Reports - Admin, Manager only
    Route::middleware('role:Admin,Manager')->group(function () {
        Route::get('/reports', [ReportController::class, 'index'])->name('admin.reports.index');
    });

    // User Management - Admin only
    Route::middleware('role:Admin')->group(function () {
        Route::resource('users', UserController::class)->names([
            'index' => 'admin.users.index',
            'create' => 'admin.users.create',
            'store' => 'admin.users.store',
            'edit' => 'admin.users.edit',
            'update' => 'admin.users.update',
            'destroy' => 'admin.users.destroy',
        ]);
    });
});

// Redirect /admin to /admin/dashboard
Route::get('/admin', function () {
    return redirect()->route('admin.dashboard');
});
