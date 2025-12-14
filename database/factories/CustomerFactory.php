<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'email' => $this->faker->unique()->safeEmail(),
            'password' => 'password',
            'full_name' => $this->faker->name(),
            'phone' => '0' . $this->faker->randomElement(['3', '5', '7', '8', '9']) . $this->faker->numerify('########'),
            'points' => 0,
            'status' => 'active',
        ];
    }
}
