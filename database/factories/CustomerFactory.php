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
            'phone' => $this->faker->phoneNumber(),
            'points' => 0,
            'status' => 'active',
        ];
    }
}
