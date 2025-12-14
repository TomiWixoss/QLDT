<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Admin',
                'description' => 'Chủ cửa hàng - Toàn quyền quản lý hệ thống',
                'permissions' => ['*'], // Full access
            ],
            [
                'name' => 'Manager',
                'description' => 'Quản lý - Quản lý sản phẩm, đơn hàng, kho, báo cáo',
                'permissions' => [
                    'view-all',
                    'manage-products',
                    'manage-orders',
                    'manage-inventory',
                    'view-reports',
                    'manage-customers',
                    'manage-promotions',
                ],
            ],
            [
                'name' => 'Sales',
                'description' => 'Nhân viên bán hàng - POS, đơn hàng, xem sản phẩm, khách hàng',
                'permissions' => [
                    'access-pos',
                    'manage-orders',
                    'view-products',
                    'view-customers',
                ],
            ],
            [
                'name' => 'Warehouse',
                'description' => 'Thủ kho - Quản lý kho, xem sản phẩm',
                'permissions' => [
                    'manage-inventory',
                    'view-products',
                ],
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
