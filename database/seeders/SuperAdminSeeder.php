<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'superadmin@guardyou.com'],
            [
                'name'              => 'Super Admin',
                'password'          => Hash::make('superadmin123'),
                'role'              => 'admin',
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Super Admin created: superadmin@guardyou.com / superadmin123');
    }
}
