<?php

namespace Tests\Feature\Database;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class MigrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_12_tables_created(): void
    {
        $tables = [
            'roles',
            'users',
            'customers',
            'categories',
            'brands',
            'suppliers',
            'products',
            'product_specs',
            'stock_movements',
            'promotions',
            'orders',
            'order_items',
        ];

        foreach ($tables as $table) {
            $this->assertTrue(
                Schema::hasTable($table),
                "Table {$table} does not exist"
            );
        }
    }

    public function test_roles_table_has_correct_columns(): void
    {
        $this->assertTrue(Schema::hasColumns('roles', [
            'id', 'name', 'description',
        ]));
    }

    public function test_users_table_has_correct_columns(): void
    {
        $this->assertTrue(Schema::hasColumns('users', [
            'id', 'role_id', 'username', 'password', 'full_name',
            'email', 'phone', 'avatar', 'status',
        ]));
    }

    public function test_customers_table_has_correct_columns(): void
    {
        $this->assertTrue(Schema::hasColumns('customers', [
            'id', 'email', 'password', 'full_name', 'phone',
            'avatar', 'google_id', 'facebook_id', 'points',
            'address', 'city', 'status',
        ]));
    }

    public function test_products_table_has_correct_columns(): void
    {
        $this->assertTrue(Schema::hasColumns('products', [
            'id', 'category_id', 'brand_id', 'sku', 'name',
            'price', 'cost', 'quantity', 'image', 'warranty_months', 'status',
        ]));
    }

    public function test_orders_table_has_correct_columns(): void
    {
        $this->assertTrue(Schema::hasColumns('orders', [
            'id', 'order_code', 'source', 'customer_id', 'user_id',
            'subtotal', 'discount', 'tax', 'total_money',
            'payment_method', 'payment_status', 'order_status',
        ]));
    }

    public function test_order_items_table_has_imei_list_column(): void
    {
        $this->assertTrue(Schema::hasColumn('order_items', 'imei_list'));
    }
}
