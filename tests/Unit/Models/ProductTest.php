<?php

namespace Tests\Unit\Models;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductSpec;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->category = Category::create(['name' => 'Phones']);
        $this->brand = Brand::create(['name' => 'Apple']);
    }

    protected Category $category;
    protected Brand $brand;

    public function test_product_belongs_to_category(): void
    {
        $product = Product::create([
            'category_id' => $this->category->id,
            'brand_id' => $this->brand->id,
            'sku' => 'TEST-001',
            'name' => 'Test Product',
            'price' => 1000000,
        ]);

        $this->assertInstanceOf(Category::class, $product->category);
        $this->assertEquals('Phones', $product->category->name);
    }

    public function test_product_belongs_to_brand(): void
    {
        $product = Product::create([
            'category_id' => $this->category->id,
            'brand_id' => $this->brand->id,
            'sku' => 'TEST-002',
            'name' => 'Test Product',
            'price' => 1000000,
        ]);

        $this->assertInstanceOf(Brand::class, $product->brand);
        $this->assertEquals('Apple', $product->brand->name);
    }

    public function test_product_has_one_product_spec(): void
    {
        $product = Product::create([
            'category_id' => $this->category->id,
            'brand_id' => $this->brand->id,
            'sku' => 'TEST-003',
            'name' => 'Test Product',
            'price' => 1000000,
        ]);

        ProductSpec::create([
            'product_id' => $product->id,
            'screen' => '6.1 inch',
            'ram' => '8GB',
        ]);

        $this->assertInstanceOf(ProductSpec::class, $product->productSpec);
        $this->assertEquals('6.1 inch', $product->productSpec->screen);
    }
}
