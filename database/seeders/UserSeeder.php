<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'department' => 'IT',
            'role' => 'admin',
        ]);

        // Regular user
        User::create([
            'name' => 'Sokha Chan',
            'email' => 'user@example.com',
            'password' => bcrypt('password'),
            'department' => 'Marketing',
            'role' => 'user',
        ]);

        // Additional sample users
        User::create([
            'name' => 'Dara Kim',
            'email' => 'dara@example.com',
            'password' => bcrypt('password'),
            'department' => 'HR',
            'role' => 'user',
        ]);

        User::create([
            'name' => 'Vicheka Sorn',
            'email' => 'vicheka@example.com',
            'password' => bcrypt('password'),
            'department' => 'Finance',
            'role' => 'user',
        ]);

        User::create([
            'name' => 'Rathana Meas',
            'email' => 'rathana@example.com',
            'password' => bcrypt('password'),
            'department' => 'IT',
            'role' => 'user',
        ]);
    }
}
