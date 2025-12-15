<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
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
