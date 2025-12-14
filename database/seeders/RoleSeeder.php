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
            ],
            [
                'name' => 'Manager',
                'description' => 'Quản lý - Quản lý sản phẩm, đơn hàng, kho, báo cáo',
            ],
            [
                'name' => 'Sales',
                'description' => 'Nhân viên bán hàng - POS, đơn hàng, xem sản phẩm, khách hàng',
            ],
            [
                'name' => 'Warehouse',
                'description' => 'Thủ kho - Quản lý kho, xem sản phẩm',
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
