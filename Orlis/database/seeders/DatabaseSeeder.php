<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $roles = [
            'admin' => 'Admin User',
            'manager' => 'Manager User',
            'staff' => 'Staff User',
            'customer' => 'Customer User',
            'shipper' => 'Shipper User',
            'warehouse_staff' => 'Warehouse Staff User',
            'supplier' => 'Supplier User',
            'guest' => 'Guest User',
        ];

        foreach ($roles as $role => $name) {
            User::factory()->create([
                'name' => $name,
                'email' => $role . '@orlis.test',
                'role' => $role,
            ]);
        }
    }
}
