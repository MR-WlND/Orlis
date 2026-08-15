<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Sophia Carter',
            'email' => 's.carter@example.com',
            'phone' => '0981 111 222',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'membership_level' => 'diamond',
        ]);

        User::create([
            'name' => 'Liam Bennett',
            'email' => 'l.bennett@example.com',
            'phone' => '0982 333 444',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'membership_level' => 'gold',
        ]);

        User::create([
            'name' => 'Olivia Hayes',
            'email' => 'o.hayes@example.com',
            'phone' => '0983 555 666',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'membership_level' => 'silver',
        ]);

        User::create([
            'name' => 'Noah Brooks',
            'email' => 'n.brooks@example.com',
            'phone' => '0984 777 888',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'membership_level' => 'classic',
        ]);
    }
}
