# Story 1.6: Staff Authentication & Role Setup

Status: ready-for-dev

## Story

As a **Staff Member**,
I want to login to the admin panel using my email and password,
So that I can access the management features based on my role.

## Acceptance Criteria

**AC1: Staff Login Page**
**Given** I am a staff member
**When** I navigate to /admin/login
**Then** I see a staff login form (separate from customer login)
**And** I see the Tact admin branding
**And** I can enter my email and password

**AC2: Successful Staff Login**
**Given** I enter correct staff credentials
**When** I submit the login form
**Then** I am logged in with the 'web' guard (staff guard)
**And** I am redirected to /admin/dashboard
**And** I see a welcome message with my name and role

**AC3: Failed Staff Login**
**Given** I enter incorrect credentials
**When** I submit the login form
**Then** I see an error message "Email hoặc mật khẩu không đúng"
**And** the failed attempt is logged
**And** I am rate limited after 5 failed attempts

**AC4: Staff Logout**
**Given** I am logged in as staff
**When** I click logout
**Then** my session is destroyed
**And** I am redirected to /admin/login

**AC5: Admin Route Protection**
**Given** I try to access /admin/\* routes without being logged in
**When** I navigate to any admin route
**Then** I am redirected to /admin/login
**And** I see a message "Vui lòng đăng nhập để tiếp tục"

**AC6: Basic Dashboard Display**
**Given** I am logged in as staff
**When** I am redirected to /admin/dashboard
**Then** I see a basic dashboard page with welcome message
**And** I see my name and role displayed
**And** I see the admin sidebar navigation

## Tasks / Subtasks

-   [ ] Task 1: Create Admin Layout (AC: 2, 6)

    -   [ ] 1.1: Create resources/views/layouts/admin.blade.php
    -   [ ] 1.2: Implement DaisyUI sidebar navigation
    -   [ ] 1.3: Add header with user info and logout button
    -   [ ] 1.4: Add responsive mobile menu

-   [ ] Task 2: Create AdminLoginController (AC: 1, 2, 3, 4)

    -   [ ] 2.1: Create app/Http/Controllers/Auth/AdminLoginController.php
    -   [ ] 2.2: Implement showLoginForm() method
    -   [ ] 2.3: Implement login() method with rate limiting
    -   [ ] 2.4: Implement logout() method

-   [ ] Task 3: Create Admin Login Request (AC: 3)

    -   [ ] 3.1: Create app/Http/Requests/AdminLoginRequest.php
    -   [ ] 3.2: Add validation rules (email required, password required)
    -   [ ] 3.3: Add Vietnamese validation messages
    -   [ ] 3.4: Implement rate limiting logic

-   [ ] Task 4: Create Admin Login View (AC: 1)

    -   [ ] 4.1: Create resources/views/admin/auth/login.blade.php
    -   [ ] 4.2: Style with DaisyUI (admin theme)
    -   [ ] 4.3: Add error message display
    -   [ ] 4.4: Add rate limit warning display

-   [ ] Task 5: Create Basic Dashboard (AC: 6)

    -   [ ] 5.1: Create app/Http/Controllers/Admin/DashboardController.php
    -   [ ] 5.2: Create resources/views/admin/dashboard.blade.php
    -   [ ] 5.3: Display welcome message with user name and role
    -   [ ] 5.4: Add placeholder stat cards

-   [ ] Task 6: Configure Admin Routes (AC: 5)

    -   [ ] 6.1: Add admin routes group in routes/web.php
    -   [ ] 6.2: Add GET /admin/login route
    -   [ ] 6.3: Add POST /admin/login route
    -   [ ] 6.4: Add POST /admin/logout route
    -   [ ] 6.5: Add GET /admin/dashboard route
    -   [ ] 6.6: Apply 'auth' middleware to protected routes
    -   [ ] 6.7: Add guest middleware to login route

-   [ ] Task 7: Create Authentication Middleware (AC: 5) ⚠️ MUST CREATE - FILE DOESN'T EXIST

    -   [ ] 7.1: CREATE app/Http/Middleware/Authenticate.php (file doesn't exist!)
    -   [ ] 7.2: Implement redirectTo() method for admin/customer routing
    -   [ ] 7.3: Add session flash message for admin redirect

-   [ ] Task 8: Create RedirectIfAuthenticated Middleware (AC: 1) ⚠️ MUST CREATE - FILE DOESN'T EXIST

    -   [ ] 8.1: CREATE app/Http/Middleware/RedirectIfAuthenticated.php (file doesn't exist!)
    -   [ ] 8.2: Implement handle() method for admin/customer routing
    -   [ ] 8.3: Register both middleware in bootstrap/app.php

-   [ ] Task 9: Update User Model (AC: 2, 6) ⚠️ REQUIRED FOR STORY 1.7

    -   [ ] 9.1: Add hasRole() helper method to app/Models/User.php
    -   [ ] 9.2: Add hasAnyRole() helper method to app/Models/User.php

-   [ ] Task 10: Update UserSeeder (AC: All) 💡 ENHANCEMENT

    -   [ ] 10.1: Add Manager test user to database/seeders/UserSeeder.php
    -   [ ] 10.2: Add Sales test user
    -   [ ] 10.3: Add Warehouse test user

-   [ ] Task 11: Write Tests (AC: All)
    -   [ ] 11.1: Test admin login page displays correctly
    -   [ ] 11.2: Test successful login redirects to dashboard
    -   [ ] 11.3: Test failed login shows error message
    -   [ ] 11.4: Test rate limiting after 5 failed attempts
    -   [ ] 11.5: Test logout destroys session
    -   [ ] 11.6: Test unauthenticated access redirects to admin login
    -   [ ] 11.7: Test dashboard displays user info
    -   [ ] 11.8: Test authenticated user cannot access login page (redirects to dashboard)

## Dev Notes

### 📋 Quick Reference Card

```
┌─────────────────────────────────────────────────────────────┐
│ STORY 1.6 QUICK REFERENCE CARD                              │
├─────────────────────────────────────────────────────────────┤
│ MUST DO:                                                     │
│ ✓ Use 'web' guard (default Laravel guard for staff)         │
│ ✓ Use 'users' table (NOT 'customers')                       │
│ ✓ Separate login URL: /admin/login (NOT /login)             │
│ ✓ Rate limit: 5 attempts per minute                         │
│ ✓ Vietnamese error messages                                 │
│ ✓ DaisyUI admin theme (NOT Nike-inspired customer theme)    │
│ ✓ Sidebar navigation (NOT bottom nav)                       │
│ ✓ Display user role on dashboard                            │
│                                                              │
│ MUST NOT DO:                                                 │
│ ✗ Use 'customer' guard (that's for customers)               │
│ ✗ Use customer layout (use admin layout)                    │
│ ✗ Mix admin and customer login routes                       │
│ ✗ Skip rate limiting                                        │
│ ✗ English error messages                                    │
│                                                              │
│ KEY FILES TO CREATE:                                         │
│ • app/Http/Controllers/Auth/AdminLoginController.php        │
│ • app/Http/Controllers/Admin/DashboardController.php        │
│ • app/Http/Requests/AdminLoginRequest.php                   │
│ • resources/views/layouts/admin.blade.php                   │
│ • resources/views/admin/auth/login.blade.php                │
│ • resources/views/admin/dashboard.blade.php                 │
│ • app/Http/Middleware/Authenticate.php ⚠️ MUST CREATE!      │
│ • app/Http/Middleware/RedirectIfAuthenticated.php ⚠️ CREATE!│
│ • tests/Feature/Admin/AuthenticationTest.php                │
│                                                              │
│ KEY FILES TO MODIFY:                                         │
│ • routes/web.php (add admin routes)                         │
│ • app/Models/User.php (add hasRole helper methods)          │
│ • database/seeders/UserSeeder.php (add all 4 role users)    │
│ • bootstrap/app.php (register middleware)                   │
└─────────────────────────────────────────────────────────────┘
```

### Critical Architecture Decisions

**From architecture.md - Decision 2.1: Authentication Strategy**

```php
// Staff Authentication (users table):
//   - Guard: 'web' (default Laravel session guard)
//   - Middleware: auth (default)
//   - Table: users (email, password, role_id, full_name, status)
//   - Login URL: /admin/login
//   - Redirect after login: /admin/dashboard

// Customer Authentication (customers table):
//   - Guard: 'customer' (custom guard)
//   - Middleware: auth:customer
//   - Table: customers
//   - Login URL: /login
```

**From architecture.md - Decision 4.1: Layout Architecture**

```php
// Admin Layout: resources/views/layouts/admin.blade.php
// - DaisyUI components
// - Sidebar navigation
// - Information-dense
// - Functional design
```

**From architecture.md - Decision 2.2: Authorization Strategy**

```php
// 4 Roles (already in database):
// 1. Admin: Full access
// 2. Manager: All except user management
// 3. Sales: POS, orders, customers (read-only products)
// 4. Warehouse: Stock management, products (read-only)
```

### ⚠️ CRITICAL: User Model - ADD Helper Methods (Required for Story 1.7 RBAC)

```php
// app/Models/User.php - ⚠️ ADD THESE METHODS!
// The User model EXISTS but is MISSING these helper methods!
// These methods are REQUIRED for Story 1.7 (RBAC) - add them now!

// Current User model has:
// ✅ role() relationship (already exists)
// ❌ hasRole() method (MISSING - ADD THIS!)
// ❌ hasAnyRole() method (MISSING - ADD THIS!)

// ADD these methods to app/Models/User.php:

/**
 * Check if user has a specific role
 * Required for Story 1.7 RBAC implementation
 */
public function hasRole(string $roleName): bool
{
    return $this->role && $this->role->name === $roleName;
}

/**
 * Check if user has any of the specified roles
 * Required for Story 1.7 RBAC implementation
 */
public function hasAnyRole(array $roleNames): bool
{
    return $this->role && in_array($this->role->name, $roleNames);
}

// Usage examples (for Story 1.7):
// $user->hasRole('Admin')           // true if Admin
// $user->hasAnyRole(['Admin', 'Manager'])  // true if Admin OR Manager
```

### AdminLoginController Implementation

```php
// app/Http/Controllers/Auth/AdminLoginController.php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminLoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AdminLoginController extends Controller
{
    /**
     * Display admin login form
     */
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    /**
     * Handle admin login request
     */
    public function login(AdminLoginRequest $request)
    {
        // Check rate limiting
        $throttleKey = $this->throttleKey($request);

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->withErrors([
                'email' => "Quá nhiều lần đăng nhập thất bại. Vui lòng thử lại sau {$seconds} giây.",
            ])->withInput($request->only('email'));
        }

        // Attempt login with 'web' guard (default)
        if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            RateLimiter::clear($throttleKey);
            $request->session()->regenerate();

            return redirect()->intended('/admin/dashboard');
        }

        // Failed login - increment rate limiter
        RateLimiter::hit($throttleKey, 60);

        return back()->withErrors([
            'email' => 'Email hoặc mật khẩu không đúng',
        ])->withInput($request->only('email'));
    }

    /**
     * Handle admin logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/admin/login');
    }

    /**
     * Get the rate limiting throttle key
     */
    protected function throttleKey(Request $request): string
    {
        return Str::transliterate(Str::lower($request->input('email')) . '|' . $request->ip());
    }
}
```

### AdminLoginRequest Validation

```php
// app/Http/Requests/AdminLoginRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminLoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không hợp lệ',
            'password.required' => 'Vui lòng nhập mật khẩu',
        ];
    }
}
```

### DashboardController Implementation

```php
// app/Http/Controllers/Admin/DashboardController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display admin dashboard
     */
    public function index()
    {
        $user = Auth::user();

        return view('admin.dashboard', [
            'user' => $user,
        ]);
    }
}
```

### Routes Configuration

```php
// routes/web.php - ADD these routes

use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Admin\DashboardController;

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

    // Future admin routes will go here...
});

// Redirect /admin to /admin/dashboard
Route::get('/admin', function () {
    return redirect()->route('admin.dashboard');
});
```

### ⚠️ CRITICAL: Authenticate Middleware (MUST CREATE - FILE DOESN'T EXIST!)

```php
// app/Http/Middleware/Authenticate.php - ⚠️ MUST CREATE THIS FILE!
// This file does NOT exist in the project - you must create it!
namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if ($request->expectsJson()) {
            return null;
        }

        // Redirect admin routes to admin login
        if ($request->is('admin/*') || $request->is('admin')) {
            session()->flash('message', 'Vui lòng đăng nhập để tiếp tục');
            return route('admin.login');
        }

        // Redirect customer routes to customer login
        return route('login');
    }
}
```

### ⚠️ CRITICAL: RedirectIfAuthenticated Middleware (MUST CREATE - FILE DOESN'T EXIST!)

```php
// app/Http/Middleware/RedirectIfAuthenticated.php - ⚠️ MUST CREATE THIS FILE!
// This file does NOT exist in the project - you must create it!
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // Redirect admin routes to admin dashboard
                if ($request->is('admin/*') || $request->is('admin')) {
                    return redirect()->route('admin.dashboard');
                }

                // Redirect customer routes to customer home
                if ($guard === 'customer') {
                    return redirect()->route('home');
                }

                // Default redirect for web guard
                return redirect()->route('admin.dashboard');
            }
        }

        return $next($request);
    }
}
```

### ⚠️ CRITICAL: Middleware Registration (Laravel 12)

```php
// bootstrap/app.php - Register custom middleware
// ⚠️ MUST UPDATE THIS FILE to register the new middleware!

use App\Http\Middleware\Authenticate;
use App\Http\Middleware\RedirectIfAuthenticated;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Register custom middleware aliases
        $middleware->alias([
            'auth' => Authenticate::class,
            'guest' => RedirectIfAuthenticated::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
```

### Admin Layout Implementation

```blade
{{-- resources/views/layouts/admin.blade.php --}}
<!DOCTYPE html>
<html lang="vi" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin - Tact')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-base-200">
    <div class="drawer lg:drawer-open">
        <input id="admin-drawer" type="checkbox" class="drawer-toggle" />

        {{-- Main Content --}}
        <div class="drawer-content flex flex-col">
            {{-- Top Navbar --}}
            <div class="navbar bg-base-100 shadow-sm lg:hidden">
                <div class="flex-none">
                    <label for="admin-drawer" class="btn btn-square btn-ghost drawer-button">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </label>
                </div>
                <div class="flex-1">
                    <span class="text-xl font-bold">Tact Admin</span>
                </div>
            </div>

            {{-- Page Content --}}
            <main class="flex-1 p-4 lg:p-6">
                @yield('content')
            </main>
        </div>

        {{-- Sidebar --}}
        <div class="drawer-side">
            <label for="admin-drawer" aria-label="close sidebar" class="drawer-overlay"></label>
            <aside class="bg-base-100 w-64 min-h-screen flex flex-col">
                {{-- Logo --}}
                <div class="p-4 border-b">
                    <a href="{{ route('admin.dashboard') }}" class="text-2xl font-bold text-primary">
                        Tact Admin
                    </a>
                </div>

                {{-- User Info --}}
                <div class="p-4 border-b bg-base-200">
                    <div class="flex items-center gap-3">
                        <div class="avatar placeholder">
                            <div class="bg-primary text-primary-content rounded-full w-10">
                                <span>{{ substr(Auth::user()->full_name, 0, 1) }}</span>
                            </div>
                        </div>
                        <div>
                            <p class="font-medium text-sm">{{ Auth::user()->full_name }}</p>
                            <p class="text-xs text-gray-500">{{ Auth::user()->role->name ?? 'Staff' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Navigation Menu --}}
                <ul class="menu p-4 flex-1">
                    <li>
                        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            Dashboard
                        </a>
                    </li>
                    {{-- More menu items will be added in future stories --}}
                </ul>

                {{-- Logout --}}
                <div class="p-4 border-t">
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-outline btn-error w-full">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Đăng xuất
                        </button>
                    </form>
                </div>
            </aside>
        </div>
    </div>
</body>
</html>
```

### Admin Login View Implementation

```blade
{{-- resources/views/admin/auth/login.blade.php --}}
<!DOCTYPE html>
<html lang="vi" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Đăng nhập Admin - Tact</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-base-200 flex items-center justify-center p-4">
    <div class="card w-full max-w-md bg-base-100 shadow-xl">
        <div class="card-body">
            {{-- Logo --}}
            <div class="text-center mb-6">
                <h1 class="text-3xl font-bold text-primary">Tact Admin</h1>
                <p class="text-gray-500 mt-2">Đăng nhập để quản lý hệ thống</p>
            </div>

            {{-- Session Message --}}
            @if (session('message'))
                <div class="alert alert-warning mb-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    {{ session('message') }}
                </div>
            @endif

            {{-- Error Alert --}}
            @if ($errors->any())
                <div class="alert alert-error mb-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ $errors->first() }}
                </div>
            @endif

            {{-- Login Form --}}
            <form method="POST" action="{{ route('admin.login.submit') }}">
                @csrf

                {{-- Email --}}
                <div class="form-control mb-4">
                    <label class="label">
                        <span class="label-text font-medium">Email</span>
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}"
                        class="input input-bordered @error('email') input-error @enderror"
                        placeholder="admin@tact.vn" required autofocus>
                </div>

                {{-- Password --}}
                <div class="form-control mb-4">
                    <label class="label">
                        <span class="label-text font-medium">Mật khẩu</span>
                    </label>
                    <input type="password" name="password"
                        class="input input-bordered @error('password') input-error @enderror"
                        placeholder="••••••••" required>
                </div>

                {{-- Remember Me --}}
                <div class="form-control mb-6">
                    <label class="label cursor-pointer justify-start gap-3">
                        <input type="checkbox" name="remember" class="checkbox checkbox-primary checkbox-sm">
                        <span class="label-text">Ghi nhớ đăng nhập</span>
                    </label>
                </div>

                {{-- Submit Button --}}
                <div class="form-control">
                    <button type="submit" class="btn btn-primary w-full">
                        Đăng nhập
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
```

### Dashboard View Implementation

```blade
{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.admin')

@section('title', 'Dashboard - Tact Admin')

@section('content')
<div class="space-y-6">
    {{-- Welcome Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-bold">Xin chào, {{ $user->full_name }}!</h1>
            <p class="text-gray-500 mt-1">
                Vai trò: <span class="badge badge-primary">{{ $user->role->name ?? 'Staff' }}</span>
            </p>
        </div>
        <div class="mt-4 md:mt-0">
            <p class="text-sm text-gray-500">{{ now()->format('l, d/m/Y') }}</p>
        </div>
    </div>

    {{-- Placeholder Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Stat Card 1 --}}
        <div class="stat bg-base-100 rounded-box shadow">
            <div class="stat-figure text-primary">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
            </div>
            <div class="stat-title">Đơn hàng hôm nay</div>
            <div class="stat-value text-primary">0</div>
            <div class="stat-desc">Chưa có dữ liệu</div>
        </div>

        {{-- Stat Card 2 --}}
        <div class="stat bg-base-100 rounded-box shadow">
            <div class="stat-figure text-secondary">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="stat-title">Doanh thu hôm nay</div>
            <div class="stat-value text-secondary">0đ</div>
            <div class="stat-desc">Chưa có dữ liệu</div>
        </div>

        {{-- Stat Card 3 --}}
        <div class="stat bg-base-100 rounded-box shadow">
            <div class="stat-figure text-accent">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <div class="stat-title">Khách hàng mới</div>
            <div class="stat-value text-accent">0</div>
            <div class="stat-desc">Chưa có dữ liệu</div>
        </div>

        {{-- Stat Card 4 --}}
        <div class="stat bg-base-100 rounded-box shadow">
            <div class="stat-figure text-warning">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <div class="stat-title">Sản phẩm tồn kho thấp</div>
            <div class="stat-value text-warning">0</div>
            <div class="stat-desc">Chưa có dữ liệu</div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="card bg-base-100 shadow">
        <div class="card-body">
            <h2 class="card-title">Thao tác nhanh</h2>
            <div class="flex flex-wrap gap-2 mt-4">
                <button class="btn btn-outline btn-sm" disabled>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Thêm sản phẩm
                </button>
                <button class="btn btn-outline btn-sm" disabled>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    Xem đơn hàng
                </button>
                <button class="btn btn-outline btn-sm" disabled>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Xem báo cáo
                </button>
            </div>
            <p class="text-sm text-gray-500 mt-2">
                Các tính năng sẽ được kích hoạt trong các story tiếp theo.
            </p>
        </div>
    </div>
</div>
@endsection
```

### Project Structure (Story 1.6 Files)

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Auth/
│   │   │   ├── RegisterController.php      # EXISTS (Story 1.2)
│   │   │   ├── LoginController.php         # EXISTS (Story 1.3)
│   │   │   ├── GoogleAuthController.php    # EXISTS (Story 1.4)
│   │   │   ├── SetPasswordController.php   # EXISTS (Story 1.4)
│   │   │   └── AdminLoginController.php    # NEW
│   │   ├── Customer/
│   │   │   └── ProfileController.php       # EXISTS (Story 1.5)
│   │   └── Admin/
│   │       └── DashboardController.php     # NEW
│   ├── Requests/
│   │   ├── StoreCustomerRequest.php        # EXISTS (Story 1.2)
│   │   ├── LoginRequest.php                # EXISTS (Story 1.3)
│   │   ├── SetPasswordRequest.php          # EXISTS (Story 1.4)
│   │   ├── UpdateProfileRequest.php        # EXISTS (Story 1.5)
│   │   ├── ChangePasswordRequest.php       # EXISTS (Story 1.5)
│   │   └── AdminLoginRequest.php           # NEW
│   └── Middleware/
│       └── Authenticate.php                # MODIFY (admin redirect)
├── Models/
│   ├── User.php                            # EXISTS (verify role relationship)
│   └── Role.php                            # EXISTS (Story 1.1)

resources/views/
├── layouts/
│   ├── guest.blade.php                     # EXISTS (Story 1.2)
│   ├── customer.blade.php                  # EXISTS (Story 1.3)
│   └── admin.blade.php                     # NEW
├── admin/
│   ├── auth/
│   │   └── login.blade.php                 # NEW
│   └── dashboard.blade.php                 # NEW

routes/
└── web.php                                 # MODIFY (add admin routes)

tests/Feature/
└── Admin/
    └── AuthenticationTest.php              # NEW
```

### Previous Story Intelligence (Story 1.2-1.5)

**Learnings from Story 1.2-1.5:**

-   ✅ Customer guard already configured in config/auth.php
-   ✅ 'web' guard is default Laravel guard for users table
-   ✅ Rate limiting pattern established in LoginController
-   ✅ Vietnamese validation messages pattern established
-   ✅ DaisyUI form styling pattern established
-   ✅ Session flash message pattern established

**Files already created that this story uses:**

-   `config/auth.php` - Guards already configured (web for users, customer for customers)
-   `app/Models/User.php` - Verify role relationship exists
-   `app/Models/Role.php` - Already created in Story 1.1
-   `database/seeders/UserSeeder.php` - Test users with roles

**Database Seeder Reference (for testing):**

```php
// database/seeders/UserSeeder.php - VERIFY these test users exist
// Admin user: admin@tact.vn / password
// Manager user: manager@tact.vn / password
// Sales user: sales@tact.vn / password
// Warehouse user: warehouse@tact.vn / password
```

### Testing Requirements

```php
// tests/Feature/Admin/AuthenticationTest.php
namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create roles
        Role::create(['name' => 'Admin']);
        Role::create(['name' => 'Manager']);
        Role::create(['name' => 'Sales']);
        Role::create(['name' => 'Warehouse']);
    }

    public function test_admin_login_page_displays_correctly(): void
    {
        $response = $this->get('/admin/login');

        $response->assertStatus(200);
        $response->assertSee('Tact Admin');
        $response->assertSee('Đăng nhập');
    }

    public function test_staff_can_login_with_correct_credentials(): void
    {
        $role = Role::where('name', 'Admin')->first();
        $user = User::factory()->create([
            'email' => 'admin@tact.vn',
            'password' => 'password123',
            'role_id' => $role->id,
        ]);

        $response = $this->post('/admin/login', [
            'email' => 'admin@tact.vn',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/admin/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    public function test_staff_cannot_login_with_incorrect_credentials(): void
    {
        $role = Role::where('name', 'Admin')->first();
        User::factory()->create([
            'email' => 'admin@tact.vn',
            'password' => 'correctpassword',
            'role_id' => $role->id,
        ]);

        $response = $this->post('/admin/login', [
            'email' => 'admin@tact.vn',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    public function test_login_is_rate_limited_after_5_attempts(): void
    {
        $role = Role::where('name', 'Admin')->first();
        User::factory()->create([
            'email' => 'admin@tact.vn',
            'password' => 'correctpassword',
            'role_id' => $role->id,
        ]);

        // Make 5 failed attempts
        for ($i = 0; $i < 5; $i++) {
            $this->post('/admin/login', [
                'email' => 'admin@tact.vn',
                'password' => 'wrongpassword',
            ]);
        }

        // 6th attempt should be rate limited
        $response = $this->post('/admin/login', [
            'email' => 'admin@tact.vn',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertStringContainsString('Quá nhiều lần', session('errors')->first('email'));
    }

    public function test_staff_can_logout(): void
    {
        $role = Role::where('name', 'Admin')->first();
        $user = User::factory()->create(['role_id' => $role->id]);

        $response = $this->actingAs($user)
            ->post('/admin/logout');

        $response->assertRedirect('/admin/login');
        $this->assertGuest();
    }

    public function test_unauthenticated_user_is_redirected_to_admin_login(): void
    {
        $response = $this->get('/admin/dashboard');

        $response->assertRedirect('/admin/login');
    }

    public function test_dashboard_displays_user_info(): void
    {
        $role = Role::where('name', 'Manager')->first();
        $user = User::factory()->create([
            'full_name' => 'Nguyễn Văn Manager',
            'role_id' => $role->id,
        ]);

        $response = $this->actingAs($user)
            ->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Nguyễn Văn Manager');
        $response->assertSee('Manager');
    }

    public function test_authenticated_user_cannot_access_login_page(): void
    {
        $role = Role::where('name', 'Admin')->first();
        $user = User::factory()->create(['role_id' => $role->id]);

        $response = $this->actingAs($user)
            ->get('/admin/login');

        $response->assertRedirect('/admin/dashboard');
    }
}
```

### 💡 ENHANCEMENT: Complete UserSeeder (All 4 Roles)

```php
// database/seeders/UserSeeder.php - ⚠️ ENHANCE with all 4 role test users!
// Current seeder only creates Admin user - add Manager, Sales, Warehouse!

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('name', 'Admin')->first();
        $managerRole = Role::where('name', 'Manager')->first();
        $salesRole = Role::where('name', 'Sales')->first();
        $warehouseRole = Role::where('name', 'Warehouse')->first();

        // Admin user
        User::create([
            'role_id' => $adminRole->id,
            'username' => 'admin',
            'password' => 'password',
            'full_name' => 'Admin User',
            'email' => 'admin@tact.vn',
            'status' => 'active',
        ]);

        // Manager user
        User::create([
            'role_id' => $managerRole->id,
            'username' => 'manager',
            'password' => 'password',
            'full_name' => 'Manager User',
            'email' => 'manager@tact.vn',
            'status' => 'active',
        ]);

        // Sales user
        User::create([
            'role_id' => $salesRole->id,
            'username' => 'sales',
            'password' => 'password',
            'full_name' => 'Sales User',
            'email' => 'sales@tact.vn',
            'status' => 'active',
        ]);

        // Warehouse user
        User::create([
            'role_id' => $warehouseRole->id,
            'username' => 'warehouse',
            'password' => 'password',
            'full_name' => 'Warehouse User',
            'email' => 'warehouse@tact.vn',
            'status' => 'active',
        ]);
    }
}
```

### ✨ OPTIMIZATION: Quick Test Commands

```bash
# Test admin login page
php artisan test --filter test_admin_login_page_displays_correctly

# Test successful login
php artisan test --filter test_staff_can_login_with_correct_credentials

# Test rate limiting
php artisan test --filter test_login_is_rate_limited_after_5_attempts

# Test authenticated user redirect
php artisan test --filter test_authenticated_user_cannot_access_login_page

# Run all admin auth tests
php artisan test tests/Feature/Admin/AuthenticationTest.php

# Run with verbose output
php artisan test tests/Feature/Admin/AuthenticationTest.php --verbose
```

### References

| Document                             | Section               | Purpose                 |
| ------------------------------------ | --------------------- | ----------------------- |
| `docs/2-solutioning/architecture.md` | Decision 2.1          | Authentication Strategy |
| `docs/2-solutioning/architecture.md` | Decision 2.2          | Authorization Strategy  |
| `docs/2-solutioning/architecture.md` | Decision 4.1          | Layout Architecture     |
| `docs/2-solutioning/epics.md`        | Story 1.6             | Story requirements      |
| `project-context.md`                 | User Roles            | 4 roles definition      |
| `project-context.md`                 | Authentication Guards | Guard configuration     |

### Anti-Patterns to Avoid

```php
// ❌ BAD: Using customer guard for staff
Auth::guard('customer')->attempt($credentials);

// ✅ GOOD: Using web guard (default) for staff
Auth::attempt($credentials);

// ❌ BAD: Mixing admin and customer login routes
Route::get('/login', [AdminLoginController::class, 'showLoginForm']);

// ✅ GOOD: Separate routes for admin and customer
Route::get('/admin/login', [AdminLoginController::class, 'showLoginForm']);
Route::get('/login', [LoginController::class, 'showLoginForm']);

// ❌ BAD: No rate limiting
if (Auth::attempt($credentials)) { ... }

// ✅ GOOD: With rate limiting
if (RateLimiter::tooManyAttempts($key, 5)) {
    return back()->withErrors(['email' => 'Too many attempts']);
}

// ❌ BAD: English error messages
return back()->withErrors(['email' => 'Invalid credentials']);

// ✅ GOOD: Vietnamese error messages
return back()->withErrors(['email' => 'Email hoặc mật khẩu không đúng']);
```

### Troubleshooting Guide

| Issue                                  | Cause                 | Solution                                      |
| -------------------------------------- | --------------------- | --------------------------------------------- |
| "Route [admin.login] not defined"      | Routes not registered | Check routes/web.php has admin routes         |
| Login redirects to /home               | Wrong redirect config | Update Authenticate middleware                |
| "Class AdminLoginController not found" | Namespace issue       | Check namespace and use statements            |
| Rate limiting not working              | Missing RateLimiter   | Import Illuminate\Support\Facades\RateLimiter |
| Role not displaying                    | Missing relationship  | Verify User->role() relationship              |
| Middleware not working                 | Not registered        | Check bootstrap/app.php middleware aliases    |
| "guest" middleware not redirecting     | Missing middleware    | Create RedirectIfAuthenticated.php            |

### ⚠️ VALIDATION IMPROVEMENTS APPLIED

**This story was validated on 2025-12-15 and the following improvements were applied:**

1. **CRITICAL #1:** Added instructions to CREATE Authenticate middleware (file doesn't exist!)
2. **CRITICAL #2:** Added instructions to CREATE RedirectIfAuthenticated middleware (file doesn't exist!)
3. **CRITICAL #3:** Added User model helper methods (hasRole, hasAnyRole) required for Story 1.7
4. **ENHANCEMENT #1:** Added complete UserSeeder with all 4 role test users
5. **ENHANCEMENT #2:** Added middleware registration guidance for Laravel 12 (bootstrap/app.php)
6. **OPTIMIZATION #1:** Added quick test commands for faster verification

**Validation Report:** `docs/3-implementation/validation-report-1-6-2025-12-15.md`

## Dev Agent Record

### Context Reference

-   project-context.md (loaded)
-   project_context2.md (loaded)
-   docs/2-solutioning/architecture.md (lines 485-770, 901-1086)
-   docs/2-solutioning/epics.md (lines 1038-1131)
-   docs/3-implementation/1-5-customer-profile-management.md (previous story)

### Agent Model Used

{{agent_model_name_version}}

### Debug Log References

### Completion Notes List

-   Story created via BMAD create-story workflow
-   Ultimate context engine analysis completed
-   Comprehensive developer guide created

### File List

**Files to CREATE:**

-   app/Http/Controllers/Auth/AdminLoginController.php
-   app/Http/Controllers/Admin/DashboardController.php
-   app/Http/Requests/AdminLoginRequest.php
-   resources/views/layouts/admin.blade.php
-   resources/views/admin/auth/login.blade.php
-   resources/views/admin/dashboard.blade.php
-   tests/Feature/Admin/AuthenticationTest.php
-   ⚠️ app/Http/Middleware/Authenticate.php (MUST CREATE - doesn't exist!)
-   ⚠️ app/Http/Middleware/RedirectIfAuthenticated.php (MUST CREATE - doesn't exist!)

**Files to MODIFY:**

-   routes/web.php (add admin routes)
-   bootstrap/app.php (register middleware aliases)
-   app/Models/User.php (add hasRole, hasAnyRole helper methods)
-   database/seeders/UserSeeder.php (add Manager, Sales, Warehouse test users)

**Files to VERIFY:**

-   app/Models/Role.php (exists)
-   config/auth.php (web and customer guards configured)
