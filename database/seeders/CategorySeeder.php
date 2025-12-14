<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Điện thoại', 'description' => 'Điện thoại di động các loại'],
            ['name' => 'Phụ kiện', 'description' => 'Phụ kiện điện thoại'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
