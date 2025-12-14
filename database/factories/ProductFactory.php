<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'brand_id' => Brand::factory(),
            'sku' => $this->faker->unique()->ean13(),
            'name' => $this->faker->words(3, true),
            'price' => $this->faker->numberBetween(1000000, 50000000),
            'cost' => $this->faker->numberBetween(800000, 40000000),
            'quantity' => $this->faker->numberBetween(0, 100),
            'warranty_months' => 12,
            'status' => 'active',
        ];
    }
}
