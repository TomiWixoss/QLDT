<?php

namespace Tests\Feature\Database;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Role;
use App\Models\Supplier;
use App\Models\User;
use Database\Seeders\BrandSeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\CustomerSeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\SupplierSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_role_seeder_creates_4_roles(): void
    {
        $this->seed(RoleSeeder::class);

        $this->assertDatabaseCount('roles', 4);
        $this->assertDatabaseHas('roles', ['name' => 'Admin']);
        $this->assertDatabaseHas('roles', ['name' => 'Manager']);
        $this->assertDatabaseHas('roles', ['name' => 'Sales']);
        $this->assertDatabaseHas('roles', ['name' => 'Warehouse']);
    }

    public function test_role_seeder_creates_correct_permissions(): void
    {
        $this->seed(RoleSeeder::class);

        // Admin has full access
        $admin = Role::where('name', 'Admin')->first();
        $this->assertContains('*', $admin->permissions);
        $this->assertTrue($admin->hasPermission('anything'));

        // Manager has specific permissions
        $manager = Role::where('name', 'Manager')->first();
        $this->assertContains('view-all', $manager->permissions);
        $this->assertContains('manage-products', $manager->permissions);
        $this->assertContains('manage-orders', $manager->permissions);
        $this->assertContains('manage-inventory', $manager->permissions);
        $this->assertContains('view-reports', $manager->permissions);
        $this->assertTrue($manager->hasPermission('manage-products'));
        $this->assertFalse($manager->hasPermission('access-pos'));

        // Sales has POS access
        $sales = Role::where('name', 'Sales')->first();
        $this->assertContains('access-pos', $sales->permissions);
        $this->assertContains('manage-orders', $sales->permissions);
        $this->assertContains('view-products', $sales->permissions);
        $this->assertContains('view-customers', $sales->permissions);
        $this->assertTrue($sales->hasPermission('access-pos'));
        $this->assertFalse($sales->hasPermission('manage-inventory'));

        // Warehouse has inventory access
        $warehouse = Role::where('name', 'Warehouse')->first();
        $this->assertContains('manage-inventory', $warehouse->permissions);
        $this->assertContains('view-products', $warehouse->permissions);
        $this->assertTrue($warehouse->hasPermission('manage-inventory'));
        $this->assertFalse($warehouse->hasPermission('access-pos'));
    }

    public function test_user_seeder_creates_admin_user(): void
    {
        $this->seed(RoleSeeder::class);
        $this->seed(UserSeeder::class);

        $this->assertDatabaseHas('users', [
            'username' => 'admin',
            'email' => 'admin@tact.vn',
            'full_name' => 'Admin User',
        ]);

        $admin = User::where('username', 'admin')->first();
        $this->assertEquals('Admin', $admin->role->name);
    }

    public function test_customer_seeder_creates_guest_customer(): void
    {
        $this->seed(CustomerSeeder::class);

        $this->assertDatabaseHas('customers', [
            'email' => 'guest@tact.vn',
            'full_name' => 'Khách vãng lai',
            'phone' => '0000000000',
            'points' => 0,
        ]);
    }

    public function test_category_seeder_creates_2_categories(): void
    {
        $this->seed(CategorySeeder::class);

        $this->assertDatabaseCount('categories', 2);
        $this->assertDatabaseHas('categories', ['name' => 'Điện thoại']);
        $this->assertDatabaseHas('categories', ['name' => 'Phụ kiện']);
    }

    public function test_brand_seeder_creates_2_brands(): void
    {
        $this->seed(BrandSeeder::class);

        $this->assertDatabaseCount('brands', 2);
        $this->assertDatabaseHas('brands', ['name' => 'Apple']);
        $this->assertDatabaseHas('brands', ['name' => 'Samsung']);
    }

    public function test_supplier_seeder_creates_2_suppliers(): void
    {
        $this->seed(SupplierSeeder::class);

        $this->assertDatabaseCount('suppliers', 2);
        $this->assertDatabaseHas('suppliers', ['name' => 'Apple Vietnam']);
        $this->assertDatabaseHas('suppliers', ['name' => 'Samsung Vietnam']);
    }
}
