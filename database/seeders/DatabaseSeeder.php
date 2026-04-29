<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name'     => 'Admin GuardYou',
            'email'    => 'admin@guardyou.com',
            'password' => Hash::make('admin123'),
            'role'     => 'admin',
            'email_verified_at' => now(),
        ]);

        // User biasa
        User::create([
            'name'     => 'User Demo',
            'email'    => 'user@guardyou.com',
            'password' => Hash::make('user1234'),
            'role'     => 'user',
            'email_verified_at' => now(),
        ]);
    }
}
