<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('name', 'Admin')->first();

        User::create([
            'role_id' => $adminRole->id,
            'username' => 'admin',
            'password' => 'password',
            'full_name' => 'Admin User',
            'email' => 'admin@tact.vn',
            'status' => 'active',
        ]);
    }
}
