<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        Customer::create([
            'email' => 'guest@tact.vn',
            'full_name' => 'Khách vãng lai',
            'phone' => '0000000000',
            'points' => 0,
            'status' => 'active',
        ]);
    }
}
