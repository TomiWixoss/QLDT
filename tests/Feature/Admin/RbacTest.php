<?php

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
        // Test that role middleware works by checking unauthorized access returns 403
        $warehouse = $this->createUserWithRole('Warehouse');

        // Warehouse cannot access POS - this proves role middleware is working
        $response = $this->actingAs($warehouse)->get(route('admin.pos.index'));
        $response->assertStatus(403);
    }
}
