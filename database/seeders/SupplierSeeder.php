<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $suppliers = [
            [
                'name' => 'Apple Vietnam',
                'tax_code' => '0123456789',
                'phone' => '1800599942',
                'email' => 'support@apple.com.vn',
                'address' => 'Tầng 2, Vincom Center, 72 Lê Thánh Tôn, Q.1, TP.HCM',
            ],
            [
                'name' => 'Samsung Vietnam',
                'tax_code' => '0987654321',
                'phone' => '1800588889',
                'email' => 'support@samsung.com.vn',
                'address' => 'Tầng 3, Bitexco Tower, 2 Hải Triều, Q.1, TP.HCM',
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }
    }
}
