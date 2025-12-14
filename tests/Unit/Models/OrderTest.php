<?php

namespace Tests\Unit\Models;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    protected Role $role;
    protected User $user;
    protected Customer $customer;
    protected Category $category;
    protected Brand $brand;

    protected function setUp(): void
    {
        parent::setUp();

        $this->role = Role::create(['name' => 'Admin', 'description' => 'Admin']);
        $this->user = User::create([
            'role_id' => $this->role->id,
            'username' => 'admin',
            'password' => 'password',
            'full_name' => 'Admin',
            'email' => 'admin@test.com',
        ]);

        $this->customer = Customer::create([
            'email' => 'customer@test.com',
            'full_name' => 'Test Customer',
        ]);

        $this->category = Category::create(['name' => 'Phones']);
        $this->brand = Brand::create(['name' => 'Apple']);
    }

    public function test_order_belongs_to_customer(): void
    {
        $order = Order::create([
            'order_code' => 'ORD-001',
            'customer_id' => $this->customer->id,
            'user_id' => $this->user->id,
            'subtotal' => 1000000,
            'total_money' => 1000000,
        ]);

        $this->assertInstanceOf(Customer::class, $order->customer);
        $this->assertEquals('Test Customer', $order->customer->full_name);
    }

    public function test_order_belongs_to_user(): void
    {
        $order = Order::create([
            'order_code' => 'ORD-002',
            'customer_id' => $this->customer->id,
            'user_id' => $this->user->id,
            'subtotal' => 1000000,
            'total_money' => 1000000,
        ]);

        $this->assertInstanceOf(User::class, $order->user);
        $this->assertEquals('Admin', $order->user->full_name);
    }

    public function test_order_has_many_order_items(): void
    {
        $product = Product::create([
            'category_id' => $this->category->id,
            'brand_id' => $this->brand->id,
            'sku' => 'TEST-001',
            'name' => 'iPhone 15',
            'price' => 25000000,
            'quantity' => 10,
        ]);

        $order = Order::create([
            'order_code' => 'ORD-003',
            'customer_id' => $this->customer->id,
            'user_id' => $this->user->id,
            'subtotal' => 25000000,
            'total_money' => 25000000,
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => 25000000,
            'imei_list' => ['123456789012345'],
        ]);

        $this->assertCount(1, $order->orderItems);
        $this->assertEquals(['123456789012345'], $order->orderItems->first()->imei_list);
    }
}
