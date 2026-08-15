<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Admin;

class AccountSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('12345678');

        // ==== ADMINS ====
        $adminRoles = [
            'admin' => 'Admin Orlis',
            'manager' => 'Manager Orlis',
            'staff' => 'Staff Orlis',
            'warehouse_staff' => 'Warehouse Orlis',
            'editor' => 'Editor Orlis',
            'shipper' => 'Shipper Orlis',
            'supplier' => 'Supplier Orlis',
        ];

        foreach ($adminRoles as $role => $name) {
            Admin::updateOrCreate(
                ['email' => $role . '@example.com'],
                [
                    'name' => $name,
                    'password' => $password,
                    'role' => $role,
                    'status' => 1,
                    'phone' => '090' . rand(1000000, 9999999),
                ]
            );
        }

        // ==== USERS ====
        $userRoles = [
            'customer' => 'Customer Orlis',
            'shipper' => 'Shipper Orlis',
            'supplier' => 'Supplier Orlis',
            'guest' => 'Guest Orlis',
        ];

        foreach ($userRoles as $role => $name) {
            User::updateOrCreate(
                ['email' => $role . '@example.com'],
                [
                    'name' => $name,
                    'password' => $password,
                    'role' => $role,
                    'membership_level' => 'classic',
                    'phone' => '091' . rand(1000000, 9999999),
                ]
            );
        }
    }
}
