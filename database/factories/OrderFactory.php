<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        $subtotal = $this->faker->numberBetween(1000000, 50000000);

        return [
            'order_code' => 'ORD-' . $this->faker->unique()->numerify('######'),
            'source' => $this->faker->randomElement(['web', 'store']),
            'customer_id' => Customer::factory(),
            'user_id' => null,
            'subtotal' => $subtotal,
            'discount' => 0,
            'tax' => 0,
            'total_money' => $subtotal,
            'payment_method' => 'cod',
            'payment_status' => 'unpaid',
            'order_status' => 'pending',
        ];
    }
}
