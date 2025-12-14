<?php

namespace Tests\Feature\Database;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\Role;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TriggerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create required data in correct order
        $role = Role::create(['name' => 'Admin', 'description' => 'Admin']);
        $this->user = User::create([
            'role_id' => $role->id,
            'username' => 'admin',
            'password' => 'password',
            'full_name' => 'Admin',
            'email' => 'admin@test.com',
        ]);

        $this->category = Category::create(['name' => 'Phones']);
        $this->brand = Brand::create(['name' => 'Apple']);
    }

    protected Category $category;
    protected Brand $brand;

    public function test_update_stock_trigger_increases_quantity_on_stock_in(): void
    {
        $product = Product::create([
            'category_id' => $this->category->id,
            'brand_id' => $this->brand->id,
            'sku' => 'TEST-001',
            'name' => 'Test Product',
            'price' => 1000000,
            'quantity' => 10,
        ]);

        StockMovement::create([
            'product_id' => $product->id,
            'user_id' => $this->user->id,
            'type' => 'in',
            'quantity' => 5,
        ]);

        $this->assertEquals(15, $product->fresh()->quantity);
    }

    public function test_update_stock_trigger_decreases_quantity_on_stock_out(): void
    {
        $product = Product::create([
            'category_id' => $this->category->id,
            'brand_id' => $this->brand->id,
            'sku' => 'TEST-002',
            'name' => 'Test Product 2',
            'price' => 1000000,
            'quantity' => 10,
        ]);

        StockMovement::create([
            'product_id' => $product->id,
            'user_id' => $this->user->id,
            'type' => 'out',
            'quantity' => 3,
        ]);

        $this->assertEquals(7, $product->fresh()->quantity);
    }

    public function test_add_points_trigger_adds_points_on_order_completed(): void
    {
        $customer = Customer::create([
            'email' => 'test@test.com',
            'full_name' => 'Test Customer',
            'points' => 0,
        ]);

        $order = Order::create([
            'order_code' => 'ORD-001',
            'customer_id' => $customer->id,
            'user_id' => $this->user->id,
            'subtotal' => 25000000,
            'total_money' => 25000000,
            'order_status' => 'pending',
        ]);

        // Update to completed - trigger should add points
        $order->update(['order_status' => 'completed']);

        // 25,000,000 / 100,000 = 250 points
        $this->assertEquals(250, $customer->fresh()->points);
    }

    public function test_add_points_trigger_does_not_add_points_for_guest_order(): void
    {
        $order = Order::create([
            'order_code' => 'ORD-002',
            'customer_id' => null, // Guest order
            'user_id' => $this->user->id,
            'subtotal' => 10000000,
            'total_money' => 10000000,
            'order_status' => 'pending',
        ]);

        $order->update(['order_status' => 'completed']);

        // No error should occur
        $this->assertEquals('completed', $order->fresh()->order_status);
    }

    public function test_stock_trigger_performance_under_100ms(): void
    {
        $product = Product::create([
            'category_id' => $this->category->id,
            'brand_id' => $this->brand->id,
            'sku' => 'PERF-001',
            'name' => 'Performance Test',
            'price' => 1000000,
            'quantity' => 100,
        ]);

        $start = microtime(true);

        StockMovement::create([
            'product_id' => $product->id,
            'user_id' => $this->user->id,
            'type' => 'out',
            'quantity' => 1,
        ]);

        $duration = (microtime(true) - $start) * 1000;

        $this->assertLessThan(100, $duration, "Stock trigger took {$duration}ms, expected < 100ms");
    }
}
