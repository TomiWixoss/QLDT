<?php

namespace Tests\Feature\Database;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\BrandSeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\CustomerSeeder;
use Database\Seeders\RoleSeeder;
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
}
