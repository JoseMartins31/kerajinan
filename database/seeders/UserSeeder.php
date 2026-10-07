<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => 'active',
        ]);

        User::create([
            'username' => 'johndoe',
            'email' => 'johndoe@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'status' => 'active',
        ]);

        User::create([
            'username' => 'janesmith',
            'email' => 'janesmith@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'status' => 'active',
        ]);

        User::create([
            'username' => 'craftmaker',
            'email' => 'craftmaker@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'status' => 'active',
        ]);

        User::create([
            'username' => 'artisan_bali',
            'email' => 'artisan_bali@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'status' => 'active',
        ]);

        User::create([
            'username' => 'suspended_user',
            'email' => 'suspended@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'status' => 'inactive',
        ]);
    }
}
