<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Admin::create([
            'name' => 'Elara Vance',
            'email' => 'e.vance@avenue.com',
            'phone' => '0901 234 567',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => 1,
        ]);

        Admin::create([
            'name' => 'Julian Thorne',
            'email' => 'j.thorne@avenue.com',
            'phone' => '0902 345 678',
            'password' => Hash::make('password'),
            'role' => 'editor',
            'status' => 1,
        ]);

        Admin::create([
            'name' => 'Marcus Rove',
            'email' => 'm.rove@avenue.com',
            'phone' => '0903 456 789',
            'password' => Hash::make('password'),
            'role' => 'warehouse_staff',
            'status' => 0,
        ]);
    }
}
