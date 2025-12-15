# Story 1.7: Role-Based Access Control (RBAC)

Status: Done

## Story

As a **System**,
I want to enforce role-based access control,
So that staff members can only access features appropriate for their role.

## Acceptance Criteria

**AC1: Sales Staff - Allowed Routes**
**Given** I am logged in as a Sales staff
**When** I try to access allowed routes (POS, orders, products view, customers)
**Then** I can access these features successfully
**And** I see the appropriate navigation menu items

**AC2: Sales Staff - Restricted Routes**
**Given** I am logged in as a Sales staff
**When** I try to access restricted routes (user management, reports, inventory management)
**Then** I see a 403 Forbidden error page
**And** I see a message "Bạn không có quyền truy cập trang này"
**And** the attempt is logged for security audit

**AC3: Warehouse Staff - Allowed Routes**
**Given** I am logged in as a Warehouse staff
**When** I try to access allowed routes (inventory management, stock movements, products view)
**Then** I can access these features successfully

**AC4: Warehouse Staff - Restricted Routes**
**Given** I am logged in as a Warehouse staff
**When** I try to access restricted routes (POS, orders, customers, user management)
**Then** I see a 403 Forbidden error page

**AC5: Manager - Full Access Except User Management**
**Given** I am logged in as a Manager
**When** I try to access any route except user management
**Then** I can access all features successfully

**AC6: Admin - Full Access**
**Given** I am logged in as an Admin
**When** I try to access any route in the system
**Then** I have full access to all features

## Tasks / Subtasks

-   [x] Task 1: Create CheckRole Middleware (AC: 1, 2, 3, 4, 5, 6)

    -   [x] 1.1: Create app/Http/Middleware/CheckRole.php
    -   [x] 1.2: Implement handle() method with role checking logic
    -   [x] 1.3: Return 403 response with Vietnamese message for unauthorized access
    -   [x] 1.4: Log unauthorized access attempts for security audit
    -   [x] 1.5: Register middleware alias 'role' in bootstrap/app.php

-   [x] Task 2: Define Gates in AppServiceProvider (AC: All)

    -   [x] 2.1: Add Gate definitions in boot() method of AppServiceProvider
    -   [x] 2.2: Define 'manage-users' gate (Admin only)
    -   [x] 2.3: Define 'manage-products' gate (Admin, Manager, Warehouse)
    -   [x] 2.4: Define 'manage-orders' gate (Admin, Manager, Sales)
    -   [x] 2.5: Define 'manage-inventory' gate (Admin, Manager, Warehouse)
    -   [x] 2.6: Define 'view-reports' gate (Admin, Manager)
    -   [x] 2.7: Define 'access-pos' gate (Admin, Manager, Sales)
    -   [x] 2.8: Define 'view-customers' gate (Admin, Manager, Sales)

-   [x] Task 3: Create 403 Forbidden Error Page (AC: 2, 4)

    -   [x] 3.1: Create resources/views/errors/403.blade.php
    -   [x] 3.2: Style with DaisyUI admin theme
    -   [x] 3.3: Display Vietnamese message "Bạn không có quyền truy cập trang này"
    -   [x] 3.4: Add "Quay lại Dashboard" button

-   [x] Task 4: Update Admin Layout Navigation (AC: 1, 3, 5, 6)

    -   [x] 4.1: Update resources/views/layouts/admin.blade.php sidebar
    -   [x] 4.2: Add @can directives to show/hide menu items based on permissions
    -   [x] 4.3: Add placeholder menu items for future features (Products, Orders, Inventory, Users, Reports, POS)

-   [x] Task 5: Create Placeholder Admin Routes with RBAC (AC: All)

    -   [x] 5.1: Add placeholder routes for /admin/products (view-products permission)
    -   [x] 5.2: Add placeholder routes for /admin/orders (manage-orders permission)
    -   [x] 5.3: Add placeholder routes for /admin/inventory (manage-inventory permission)
    -   [x] 5.4: Add placeholder routes for /admin/users (manage-users permission - Admin only)
    -   [x] 5.5: Add placeholder routes for /admin/reports (view-reports permission)
    -   [x] 5.6: Add placeholder routes for /admin/pos (access-pos permission)
    -   [x] 5.7: Add placeholder routes for /admin/customers (view-customers permission)

-   [x] Task 6: Create Placeholder Controllers (AC: All)

    -   [x] 6.1: Create app/Http/Controllers/Admin/ProductController.php (placeholder index)
    -   [x] 6.2: Create app/Http/Controllers/Admin/OrderController.php (placeholder index)
    -   [x] 6.3: Create app/Http/Controllers/Admin/InventoryController.php (placeholder index)
    -   [x] 6.4: Create app/Http/Controllers/Admin/UserController.php (placeholder index)
    -   [x] 6.5: Create app/Http/Controllers/Admin/ReportController.php (placeholder index)
    -   [x] 6.6: Create app/Http/Controllers/Admin/PosController.php (placeholder index)
    -   [x] 6.7: Create app/Http/Controllers/Admin/CustomerController.php (placeholder index)

-   [x] Task 7: Create Placeholder Views (AC: All)

    -   [x] 7.1: Create resources/views/admin/products/index.blade.php (placeholder)
    -   [x] 7.2: Create resources/views/admin/orders/index.blade.php (placeholder)
    -   [x] 7.3: Create resources/views/admin/inventory/index.blade.php (placeholder)
    -   [x] 7.4: Create resources/views/admin/users/index.blade.php (placeholder)
    -   [x] 7.5: Create resources/views/admin/reports/index.blade.php (placeholder)
    -   [x] 7.6: Create resources/views/admin/pos/index.blade.php (placeholder)
    -   [x] 7.7: Create resources/views/admin/customers/index.blade.php (placeholder)

-   [x] Task 8: Write Tests (AC: All)
    -   [x] 8.1: Test Sales can access POS, orders, products view, customers
    -   [x] 8.2: Test Sales cannot access user management, reports, inventory
    -   [x] 8.3: Test Warehouse can access inventory, stock movements, products view
    -   [x] 8.4: Test Warehouse cannot access POS, orders, customers, user management
    -   [x] 8.5: Test Manager can access all except user management
    -   [x] 8.6: Test Admin can access all routes
    -   [x] 8.7: Test 403 page displays correct Vietnamese message
    -   [x] 8.8: Test navigation menu shows correct items per role

## Dev Notes

### 📋 Quick Reference Card

```
┌─────────────────────────────────────────────────────────────┐
│ STORY 1.7 QUICK REFERENCE CARD                              │
├─────────────────────────────────────────────────────────────┤
│ MUST DO:                                                     │
│ ✓ Use Laravel Gates for permission checking                 │
│ ✓ Use CheckRole middleware for route protection             │
│ ✓ Vietnamese error messages on 403 page                     │
│ ✓ Log unauthorized access attempts                          │
│ ✓ Dynamic navigation based on user role                     │
│ ✓ Use @can Blade directives for menu visibility             │
│ ✓ Create placeholder routes/controllers for future features │
│                                                              │
│ MUST NOT DO:                                                 │
│ ✗ Hardcode role checks in controllers (use Gates/Policies)  │
│ ✗ Skip logging unauthorized access                          │
│ ✗ English error messages                                    │
│ ✗ Implement full CRUD (just placeholders for now)           │
│ ✗ Create separate AuthServiceProvider (use AppServiceProvider)│
│                                                              │
│ ROLE PERMISSIONS MATRIX:                                     │
│ ┌──────────────┬───────┬─────────┬───────┬───────────┐      │
│ │ Permission   │ Admin │ Manager │ Sales │ Warehouse │      │
│ ├──────────────┼───────┼─────────┼───────┼───────────┤      │
│ │ manage-users │   ✓   │    ✗    │   ✗   │     ✗     │      │
│ │ manage-products│  ✓   │    ✓    │   ✗   │     ✓     │      │
│ │ view-products│   ✓   │    ✓    │   ✓   │     ✓     │      │
│ │ manage-orders│   ✓   │    ✓    │   ✓   │     ✗     │      │
│ │ manage-inventory│ ✓   │    ✓    │   ✗   │     ✓     │      │
│ │ view-reports │   ✓   │    ✓    │   ✗   │     ✗     │      │
│ │ access-pos   │   ✓   │    ✓    │   ✓   │     ✗     │      │
│ │ view-customers│  ✓   │    ✓    │   ✓   │     ✗     │      │
│ └──────────────┴───────┴─────────┴───────┴───────────┘      │
│                                                              │
│ KEY FILES TO CREATE:                                         │
│ • app/Http/Middleware/CheckRole.php                         │
│ • resources/views/errors/403.blade.php                      │
│ • app/Http/Controllers/Admin/*Controller.php (7 files)      │
│ • resources/views/admin/*/index.blade.php (7 files)         │
│ • tests/Feature/Admin/RbacTest.php                          │
│                                                              │
│ KEY FILES TO MODIFY:                                         │
│ • app/Providers/AppServiceProvider.php (add Gates)          │
│ • bootstrap/app.php (register CheckRole middleware)         │
│ • routes/web.php (add admin routes with middleware)         │
│ • resources/views/layouts/admin.blade.php (dynamic nav)     │
└─────────────────────────────────────────────────────────────┘
```

### Critical Architecture Decisions

**From architecture.md - Decision 2.2: Authorization Strategy**

```php
// RBAC với Laravel Gates & Policies
// 4 Roles (đã có trong database):
// 1. Admin: Full access
// 2. Manager: All except user management
// 3. Sales: POS, orders, customers (read-only products)
// 4. Warehouse: Stock management, products (read-only)

// Implementation:
// - Define Gates trong AppServiceProvider
// - Middleware: role:admin,manager
// - Blade directives: @can('manage-products')
```

> **⚠️ Provider Clarification:** Architecture.md mentions `AuthServiceProvider`, but Laravel 12 best practice is to use `AppServiceProvider` for simple Gate definitions. `AuthServiceProvider` is only needed for complex policy mappings.

### ✅ EXISTING CODE - User Model Already Has Helper Methods

```php
// app/Models/User.php - ALREADY EXISTS! ✅
// These methods were added in Story 1.6

public function hasRole(string $roleName): bool
{
    return $this->role && $this->role->name === $roleName;
}

public function hasAnyRole(array $roleNames): bool
{
    return $this->role && in_array($this->role->name, $roleNames);
}

// Usage:
// $user->hasRole('Admin')           // true if Admin
// $user->hasAnyRole(['Admin', 'Manager'])  // true if Admin OR Manager
```

### ✅ EXISTING CODE - Role Model Has Permission Check

```php
// app/Models/Role.php - ALREADY EXISTS! ✅

public function hasPermission(string $permission): bool
{
    if ($this->name === 'Admin') {
        return true; // Admin has all permissions
    }
    return in_array($permission, $this->permissions ?? []);
}
```

### CheckRole Middleware Implementation

```php
// app/Http/Middleware/CheckRole.php - CREATE THIS FILE
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles  Allowed roles (e.g., 'Admin', 'Manager')
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = Auth::user();

        // Check if user is authenticated
        if (!$user) {
            return redirect()->route('admin.login');
        }

        // Check if user has any of the allowed roles
        if ($user->hasAnyRole($roles)) {
            return $next($request);
        }

        // Log unauthorized access attempt
        Log::warning('Unauthorized access attempt', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'user_role' => $user->role?->name,
            'attempted_route' => $request->path(),
            'ip' => $request->ip(),
            'timestamp' => now()->toIso8601String(),
        ]);

        // Return 403 Forbidden
        abort(403, 'Bạn không có quyền truy cập trang này');
    }
}
```

### Security Logging Configuration

```php
// config/logging.php - ADD security channel for audit logs
'channels' => [
    // ... existing channels ...

    'security' => [
        'driver' => 'daily',
        'path' => storage_path('logs/security.log'),
        'level' => 'warning',
        'days' => 30, // Keep 30 days of security logs
    ],
],

// UPDATE CheckRole middleware to use security channel:
// Change: Log::warning(...)
// To:     Log::channel('security')->warning(...)
```

> **⚠️ Production Note:** Security logs are stored separately in `storage/logs/security.log` with 30-day rotation to prevent disk space issues while maintaining audit trail.

### Gate Definitions in AppServiceProvider

```php
// app/Providers/AppServiceProvider.php - UPDATE THIS FILE
namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // ============================================
        // RBAC GATES - Story 1.7
        // ============================================

        // Admin only - User Management
        Gate::define('manage-users', function ($user) {
            return $user->hasRole('Admin');
        });

        // Admin, Manager, Warehouse - Product Management
        Gate::define('manage-products', function ($user) {
            return $user->hasAnyRole(['Admin', 'Manager', 'Warehouse']);
        });

        // All staff can view products
        Gate::define('view-products', function ($user) {
            return $user->hasAnyRole(['Admin', 'Manager', 'Sales', 'Warehouse']);
        });

        // Admin, Manager, Sales - Order Management
        Gate::define('manage-orders', function ($user) {
            return $user->hasAnyRole(['Admin', 'Manager', 'Sales']);
        });

        // Admin, Manager, Warehouse - Inventory Management
        Gate::define('manage-inventory', function ($user) {
            return $user->hasAnyRole(['Admin', 'Manager', 'Warehouse']);
        });

        // Admin, Manager - Reports
        Gate::define('view-reports', function ($user) {
            return $user->hasAnyRole(['Admin', 'Manager']);
        });

        // Admin, Manager, Sales - POS Access
        Gate::define('access-pos', function ($user) {
            return $user->hasAnyRole(['Admin', 'Manager', 'Sales']);
        });

        // Admin, Manager, Sales - Customer Management
        Gate::define('view-customers', function ($user) {
            return $user->hasAnyRole(['Admin', 'Manager', 'Sales']);
        });
    }
}
```

### Middleware Registration in bootstrap/app.php

```php
// bootstrap/app.php - UPDATE THIS FILE
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\CheckRole;
use App\Http\Middleware\RedirectIfAuthenticated;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Register custom middleware aliases
        $middleware->alias([
            'auth' => Authenticate::class,
            'guest' => RedirectIfAuthenticated::class,
            'role' => CheckRole::class, // ← ADD THIS LINE
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
```

### Routes Configuration with RBAC

```php
// routes/web.php - UPDATE ADMIN ROUTES SECTION

use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PosController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\AdminLoginController;

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

    // Products - All staff can view, Admin/Manager/Warehouse can manage
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
        Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
    });
});

// Redirect /admin to /admin/dashboard
Route::get('/admin', function () {
    return redirect()->route('admin.dashboard');
});
```

### 403 Error Page

```blade
{{-- resources/views/errors/403.blade.php --}}
<!DOCTYPE html>
<html lang="vi" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Không có quyền truy cập</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .bg-grid-pattern {
            background-image:
                linear-gradient(to right, #e5e7eb 1px, transparent 1px),
                linear-gradient(to bottom, #e5e7eb 1px, transparent 1px);
            background-size: 20px 20px;
        }
    </style>
</head>
<body class="min-h-screen bg-base-200 flex items-center justify-center p-4 relative">
    {{-- Background pattern for visual interest --}}
    <div class="absolute inset-0 bg-grid-pattern opacity-5"></div>

    <div class="card w-full max-w-md bg-base-100 shadow-xl relative z-10">
        <div class="card-body text-center">
            {{-- Error Icon with animation --}}
            <div class="text-error mb-4">
                <svg class="w-24 h-24 mx-auto animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>

            {{-- Error Code --}}
            <h1 class="text-6xl font-bold text-error">403</h1>

            {{-- Error Message --}}
            <h2 class="text-xl font-semibold mt-4">Không có quyền truy cập</h2>
            <p class="text-gray-500 mt-2">
                {{ $exception->getMessage() ?: 'Bạn không có quyền truy cập trang này' }}
            </p>

            {{-- User Info (if logged in) --}}
            @auth
            <div class="badge badge-outline mt-2">
                Đăng nhập: {{ Auth::user()->email }} ({{ Auth::user()->role?->name }})
            </div>
            @endauth

            {{-- Action Buttons --}}
            <div class="card-actions justify-center mt-6 gap-2">
                <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Quay lại Dashboard
                </a>
                <button onclick="history.back()" class="btn btn-ghost">
                    Quay lại trang trước
                </button>
            </div>

            {{-- Help Text --}}
            <p class="text-sm text-gray-400 mt-4">
                Nếu bạn cho rằng đây là lỗi, vui lòng liên hệ quản trị viên.
            </p>
        </div>
    </div>
</body>
</html>
```

### Updated Admin Layout with Dynamic Navigation

```blade
{{-- resources/views/layouts/admin.blade.php - UPDATE NAVIGATION SECTION --}}
{{-- Replace the <ul class="menu p-4 flex-1"> section with: --}}

<ul class="menu p-4 flex-1">
    {{-- Dashboard - All staff --}}
    <li>
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            Dashboard
        </a>
    </li>

    {{-- POS - Admin, Manager, Sales --}}
    @can('access-pos')
    <li>
        <a href="{{ route('admin.pos.index') }}" class="{{ request()->routeIs('admin.pos.*') ? 'active' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
            </svg>
            POS
        </a>
    </li>
    @endcan

    {{-- Products - All staff can view --}}
    @can('view-products')
    <li>
        <a href="{{ route('admin.products.index') }}" class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
            </svg>
            Sản phẩm
        </a>
    </li>
    @endcan

    {{-- Orders - Admin, Manager, Sales --}}
    @can('manage-orders')
    <li>
        <a href="{{ route('admin.orders.index') }}" class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
            </svg>
            Đơn hàng
        </a>
    </li>
    @endcan

    {{-- Customers - Admin, Manager, Sales --}}
    @can('view-customers')
    <li>
        <a href="{{ route('admin.customers.index') }}" class="{{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            Khách hàng
        </a>
    </li>
    @endcan

    {{-- Inventory - Admin, Manager, Warehouse --}}
    @can('manage-inventory')
    <li>
        <a href="{{ route('admin.inventory.index') }}" class="{{ request()->routeIs('admin.inventory.*') ? 'active' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
            Kho hàng
        </a>
    </li>
    @endcan

    {{-- Reports - Admin, Manager --}}
    @can('view-reports')
    <li>
        <a href="{{ route('admin.reports.index') }}" class="{{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            Báo cáo
        </a>
    </li>
    @endcan

    {{-- User Management - Admin only --}}
    @can('manage-users')
    <li>
        <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            Quản lý người dùng
        </a>
    </li>
    @endcan
</ul>
```

### Placeholder Controllers (Consolidated Pattern)

```php
// ============================================
// PATTERN: All placeholder controllers follow this structure
// Location: app/Http/Controllers/Admin/{Name}Controller.php
// ============================================

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class {Name}Controller extends Controller
{
    public function index()
    {
        return view('admin.{folder}.index');
    }
}

// ============================================
// CREATE THESE 7 CONTROLLERS using the pattern above:
// ============================================
// 1. ProductController   → view: admin.products.index
// 2. OrderController     → view: admin.orders.index
// 3. InventoryController → view: admin.inventory.index
// 4. CustomerController  → view: admin.customers.index
// 5. PosController       → view: admin.pos.index
// 6. ReportController    → view: admin.reports.index
// 7. UserController      → view: admin.users.index
```

### Placeholder View Template (Example Pattern)

```blade
{{-- resources/views/admin/products/index.blade.php - CREATE THIS FILE --}}
{{-- Use this pattern for all placeholder views --}}
@extends('layouts.admin')

@section('title', 'Sản phẩm - Tact Admin')

@section('content')
<div class="space-y-6">
    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-bold">Quản lý sản phẩm</h1>
            <p class="text-gray-500 mt-1">Danh sách sản phẩm trong hệ thống</p>
        </div>
    </div>

    {{-- Placeholder Content --}}
    <div class="card bg-base-100 shadow">
        <div class="card-body">
            <div class="flex flex-col items-center justify-center py-12">
                <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
                <h3 class="text-lg font-medium text-gray-500 mt-4">Tính năng đang phát triển</h3>
                <p class="text-gray-400 mt-2">Quản lý sản phẩm sẽ được triển khai trong Epic 3</p>
            </div>
        </div>
    </div>
</div>
@endsection
```

### Test Implementation

```php
// tests/Feature/Admin/RbacTest.php - CREATE THIS FILE
namespace Tests\Feature\Admin;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class RbacTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);
    }

    protected function createUserWithRole(string $roleName): User
    {
        $role = Role::where('name', $roleName)->first();
        return User::factory()->create(['role_id' => $role->id]);
    }

    // ============================================
    // ADMIN TESTS - Full Access
    // ============================================

    public function test_admin_can_access_all_routes(): void
    {
        $admin = $this->createUserWithRole('Admin');

        $routes = [
            'admin.dashboard',
            'admin.products.index',
            'admin.orders.index',
            'admin.inventory.index',
            'admin.customers.index',
            'admin.pos.index',
            'admin.reports.index',
            'admin.users.index',
        ];

        foreach ($routes as $route) {
            $response = $this->actingAs($admin)->get(route($route));
            $response->assertStatus(200);
        }
    }

    // ============================================
    // MANAGER TESTS - All except user management
    // ============================================

    public function test_manager_can_access_allowed_routes(): void
    {
        $manager = $this->createUserWithRole('Manager');

        $allowedRoutes = [
            'admin.dashboard',
            'admin.products.index',
            'admin.orders.index',
            'admin.inventory.index',
            'admin.customers.index',
            'admin.pos.index',
            'admin.reports.index',
        ];

        foreach ($allowedRoutes as $route) {
            $response = $this->actingAs($manager)->get(route($route));
            $response->assertStatus(200);
        }
    }

    public function test_manager_cannot_access_user_management(): void
    {
        $manager = $this->createUserWithRole('Manager');

        $response = $this->actingAs($manager)->get(route('admin.users.index'));
        $response->assertStatus(403);
    }

    // ============================================
    // SALES TESTS - POS, orders, products view, customers
    // ============================================

    public function test_sales_can_access_allowed_routes(): void
    {
        $sales = $this->createUserWithRole('Sales');

        $allowedRoutes = [
            'admin.dashboard',
            'admin.products.index',
            'admin.orders.index',
            'admin.customers.index',
            'admin.pos.index',
        ];

        foreach ($allowedRoutes as $route) {
            $response = $this->actingAs($sales)->get(route($route));
            $response->assertStatus(200);
        }
    }

    public function test_sales_cannot_access_restricted_routes(): void
    {
        $sales = $this->createUserWithRole('Sales');

        $restrictedRoutes = [
            'admin.inventory.index',
            'admin.reports.index',
            'admin.users.index',
        ];

        foreach ($restrictedRoutes as $route) {
            $response = $this->actingAs($sales)->get(route($route));
            $response->assertStatus(403);
        }
    }

    // ============================================
    // WAREHOUSE TESTS - Inventory, products view
    // ============================================

    public function test_warehouse_can_access_allowed_routes(): void
    {
        $warehouse = $this->createUserWithRole('Warehouse');

        $allowedRoutes = [
            'admin.dashboard',
            'admin.products.index',
            'admin.inventory.index',
        ];

        foreach ($allowedRoutes as $route) {
            $response = $this->actingAs($warehouse)->get(route($route));
            $response->assertStatus(200);
        }
    }

    public function test_warehouse_cannot_access_restricted_routes(): void
    {
        $warehouse = $this->createUserWithRole('Warehouse');

        $restrictedRoutes = [
            'admin.orders.index',
            'admin.customers.index',
            'admin.pos.index',
            'admin.reports.index',
            'admin.users.index',
        ];

        foreach ($restrictedRoutes as $route) {
            $response = $this->actingAs($warehouse)->get(route($route));
            $response->assertStatus(403);
        }
    }

    // ============================================
    // 403 PAGE TESTS
    // ============================================

    public function test_403_page_displays_vietnamese_message(): void
    {
        $sales = $this->createUserWithRole('Sales');

        $response = $this->actingAs($sales)->get(route('admin.users.index'));

        $response->assertStatus(403);
        $response->assertSee('Bạn không có quyền truy cập trang này');
    }

    // ============================================
    // NAVIGATION TESTS
    // ============================================

    public function test_admin_sees_all_menu_items(): void
    {
        $admin = $this->createUserWithRole('Admin');

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertSee('Quản lý người dùng');
        $response->assertSee('Báo cáo');
        $response->assertSee('Kho hàng');
    }

    public function test_sales_does_not_see_restricted_menu_items(): void
    {
        $sales = $this->createUserWithRole('Sales');

        $response = $this->actingAs($sales)->get(route('admin.dashboard'));

        $response->assertDontSee('Quản lý người dùng');
        $response->assertDontSee('Báo cáo');
        $response->assertDontSee('Kho hàng');
    }

    // ============================================
    // GATE TESTS - Direct Gate Authorization
    // ============================================

    public function test_manage_users_gate_allows_admin_only(): void
    {
        $admin = $this->createUserWithRole('Admin');
        $manager = $this->createUserWithRole('Manager');
        $sales = $this->createUserWithRole('Sales');
        $warehouse = $this->createUserWithRole('Warehouse');

        $this->assertTrue(Gate::forUser($admin)->allows('manage-users'));
        $this->assertFalse(Gate::forUser($manager)->allows('manage-users'));
        $this->assertFalse(Gate::forUser($sales)->allows('manage-users'));
        $this->assertFalse(Gate::forUser($warehouse)->allows('manage-users'));
    }

    public function test_manage_products_gate_allows_correct_roles(): void
    {
        $admin = $this->createUserWithRole('Admin');
        $manager = $this->createUserWithRole('Manager');
        $warehouse = $this->createUserWithRole('Warehouse');
        $sales = $this->createUserWithRole('Sales');

        $this->assertTrue(Gate::forUser($admin)->allows('manage-products'));
        $this->assertTrue(Gate::forUser($manager)->allows('manage-products'));
        $this->assertTrue(Gate::forUser($warehouse)->allows('manage-products'));
        $this->assertFalse(Gate::forUser($sales)->allows('manage-products'));
    }

    public function test_access_pos_gate_allows_correct_roles(): void
    {
        $admin = $this->createUserWithRole('Admin');
        $manager = $this->createUserWithRole('Manager');
        $sales = $this->createUserWithRole('Sales');
        $warehouse = $this->createUserWithRole('Warehouse');

        $this->assertTrue(Gate::forUser($admin)->allows('access-pos'));
        $this->assertTrue(Gate::forUser($manager)->allows('access-pos'));
        $this->assertTrue(Gate::forUser($sales)->allows('access-pos'));
        $this->assertFalse(Gate::forUser($warehouse)->allows('access-pos'));
    }

    // ============================================
    // MIDDLEWARE REGISTRATION TEST
    // ============================================

    public function test_role_middleware_is_registered(): void
    {
        $router = app('router');
        $middlewareAliases = $router->getMiddleware();

        $this->assertArrayHasKey('role', $middlewareAliases);
        $this->assertEquals(
            \App\Http\Middleware\CheckRole::class,
            $middlewareAliases['role']
        );
    }
}
```

### Previous Story Intelligence (Story 1.6)

**Learnings from Story 1.6 - Staff Authentication:**

1. **Middleware Registration**: Laravel 12 uses `bootstrap/app.php` for middleware registration, NOT `app/Http/Kernel.php`
2. **User Model**: `hasRole()` and `hasAnyRole()` methods already exist - use them!
3. **Role Model**: `hasPermission()` method already exists for permission checking
4. **Admin Layout**: Already created with sidebar navigation - just need to add @can directives
5. **Test Users**: UserSeeder already has Admin, Manager, Sales, Warehouse test users
6. **Vietnamese Messages**: All error messages must be in Vietnamese

**Files Created in Story 1.6 (DO NOT RECREATE):**

-   `app/Http/Middleware/Authenticate.php` ✅
-   `app/Http/Middleware/RedirectIfAuthenticated.php` ✅
-   `app/Http/Controllers/Auth/AdminLoginController.php` ✅
-   `app/Http/Controllers/Admin/DashboardController.php` ✅
-   `resources/views/layouts/admin.blade.php` ✅
-   `resources/views/admin/auth/login.blade.php` ✅
-   `resources/views/admin/dashboard.blade.php` ✅

### Project Structure Notes

**Alignment with unified project structure:**

-   Controllers: `app/Http/Controllers/Admin/` ✅
-   Views: `resources/views/admin/` ✅
-   Middleware: `app/Http/Middleware/` ✅
-   Tests: `tests/Feature/Admin/` ✅

**No conflicts detected.**

### References

-   [Source: docs/2-solutioning/architecture.md#Authentication & Security - Lines 485-544]
-   [Source: docs/2-solutioning/architecture.md#Decision 2.2: Authorization Strategy]
-   [Source: docs/2-solutioning/epics.md#Story 1.7: Role-Based Access Control (RBAC) - Lines 1083-1131]
-   [Source: project-context.md#User Roles & Permissions (4 Roles)]
-   [Source: docs/3-implementation/1-6-staff-authentication-role-setup.md - Previous story learnings]

### Performance Optimization

```php
// ⚠️ CRITICAL: Prevent N+1 queries when checking roles

// ❌ BAD: N+1 query problem
$users = User::all();
foreach ($users as $user) {
    if ($user->role->name === 'Admin') { // N+1 query!
        // ...
    }
}

// ✅ GOOD: Eager load role relationship
$users = User::with('role')->get();

// ✅ BETTER: Use query scope (add to User model if needed)
public function scopeWithRole($query, string $roleName)
{
    return $query->whereHas('role', fn($q) => $q->where('name', $roleName));
}

// Usage:
$admins = User::withRole('Admin')->get();
```

### Anti-Patterns to Avoid

```php
// ❌ BAD: Hardcoding role checks in controllers
public function index()
{
    if (Auth::user()->role->name !== 'Admin') {
        abort(403);
    }
}

// ✅ GOOD: Use Gates
public function index()
{
    $this->authorize('manage-users');
}

// ❌ BAD: Checking roles in views without Gates
@if(Auth::user()->role->name === 'Admin')

// ✅ GOOD: Use @can directive
@can('manage-users')

// ❌ BAD: English error messages
abort(403, 'Access denied');

// ✅ GOOD: Vietnamese error messages
abort(403, 'Bạn không có quyền truy cập trang này');

// ❌ BAD: Not logging unauthorized access
if (!$user->hasRole('Admin')) {
    abort(403);
}

// ✅ GOOD: Log unauthorized access for security audit
Log::warning('Unauthorized access attempt', [
    'user_id' => $user->id,
    'attempted_route' => $request->path(),
]);
abort(403);
```

### Testing Requirements

**Test Coverage Required:**

-   Unit tests for CheckRole middleware
-   Feature tests for each role's access permissions
-   Feature tests for 403 error page
-   Feature tests for navigation menu visibility

**Test Commands:**

```bash
# Run all RBAC tests
php artisan test --filter=RbacTest

# Run specific test
php artisan test --filter=test_admin_can_access_all_routes
```

## Dev Agent Record

### Context Reference

-   project-context.md (loaded)
-   project_context2.md (loaded)
-   docs/2-solutioning/architecture.md (lines 485-700)
-   docs/2-solutioning/epics.md (lines 1038-1181)
-   docs/3-implementation/1-6-staff-authentication-role-setup.md (previous story)

### Agent Model Used

Claude Sonnet 4 (Kiro)

### Debug Log References

-   Fixed UserFactory phone field length (VARCHAR(15) limit) - changed from fake()->phoneNumber() to fake()->numerify('09########')
-   All 104 tests passing including 14 new RBAC tests

### Completion Notes List

-   ✅ Implemented CheckRole middleware with role checking and security logging
-   ✅ Defined 8 Gates in AppServiceProvider for permission management
-   ✅ Created 403 error page with Vietnamese message and DaisyUI styling
-   ✅ Updated admin layout with dynamic navigation using @can directives
-   ✅ Created 7 placeholder admin routes with RBAC middleware protection
-   ✅ Created 7 placeholder controllers (Product, Order, Inventory, Customer, POS, Report, User)
-   ✅ Created 7 placeholder views with "coming soon" messages
-   ✅ Wrote comprehensive test suite (14 tests, 53 assertions)
-   ✅ All acceptance criteria satisfied
-   ✅ Full test suite passes (104 tests, 308 assertions)

### Change Log

-   2025-12-15: Story 1.7 RBAC implementation complete
-   2025-12-15: Code review fixes - Added security log channel, enhanced 403 page styling

### File List

**Files Created:**

-   app/Http/Middleware/CheckRole.php
-   resources/views/errors/403.blade.php
-   app/Http/Controllers/Admin/ProductController.php
-   app/Http/Controllers/Admin/OrderController.php
-   app/Http/Controllers/Admin/InventoryController.php
-   app/Http/Controllers/Admin/CustomerController.php
-   app/Http/Controllers/Admin/PosController.php
-   app/Http/Controllers/Admin/ReportController.php
-   app/Http/Controllers/Admin/UserController.php
-   resources/views/admin/products/index.blade.php
-   resources/views/admin/orders/index.blade.php
-   resources/views/admin/inventory/index.blade.php
-   resources/views/admin/customers/index.blade.php
-   resources/views/admin/pos/index.blade.php
-   resources/views/admin/reports/index.blade.php
-   resources/views/admin/users/index.blade.php
-   tests/Feature/Admin/RbacTest.php

**Files Modified:**

-   app/Providers/AppServiceProvider.php (added 8 Gate definitions)
-   bootstrap/app.php (registered CheckRole middleware alias)
-   routes/web.php (added 7 admin routes with RBAC middleware)
-   resources/views/layouts/admin.blade.php (updated navigation with @can directives)
-   database/factories/UserFactory.php (fixed phone field length)
-   config/logging.php (added security channel for audit logs)
-   app/Http/Middleware/CheckRole.php (updated to use security log channel)
-   resources/views/errors/403.blade.php (added grid pattern background)
