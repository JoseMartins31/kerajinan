<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'username' => 'admin',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => 'active'
        ]);

        // Create Sample Users (Sellers/Customers)
        User::create([
            'username' => 'johndoe',
            'password' => Hash::make('password'),
            'role' => 'user',
            'status' => 'active'
        ]);

        User::create([
            'username' => 'janesmith',
            'password' => Hash::make('password'),
            'role' => 'user',
            'status' => 'active'
        ]);

        User::create([
            'username' => 'craftmaker',
            'password' => Hash::make('password'),
            'role' => 'user',
            'status' => 'active'
        ]);

        User::create([
            'username' => 'artisan_bali',
            'password' => Hash::make('password'),
            'role' => 'user',
            'status' => 'active'
        ]);

        // Create some inactive users for testing
        User::create([
            'username' => 'suspended_user',
            'password' => Hash::make('password'),
            'role' => 'user',
            'status' => 'inactive'
        ]);
    }
}
