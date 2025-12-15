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
        $managerRole = Role::where('name', 'Manager')->first();
        $salesRole = Role::where('name', 'Sales')->first();
        $warehouseRole = Role::where('name', 'Warehouse')->first();

        // Admin user
        User::create([
            'role_id' => $adminRole->id,
            'username' => 'admin',
            'password' => 'password',
            'full_name' => 'Admin User',
            'email' => 'admin@tact.vn',
            'status' => 'active',
        ]);

        // Manager user
        User::create([
            'role_id' => $managerRole->id,
            'username' => 'manager',
            'password' => 'password',
            'full_name' => 'Manager User',
            'email' => 'manager@tact.vn',
            'status' => 'active',
        ]);

        // Sales user
        User::create([
            'role_id' => $salesRole->id,
            'username' => 'sales',
            'password' => 'password',
            'full_name' => 'Sales User',
            'email' => 'sales@tact.vn',
            'status' => 'active',
        ]);

        // Warehouse user
        User::create([
            'role_id' => $warehouseRole->id,
            'username' => 'warehouse',
            'password' => 'password',
            'full_name' => 'Warehouse User',
            'email' => 'warehouse@tact.vn',
            'status' => 'active',
        ]);
    }
}
