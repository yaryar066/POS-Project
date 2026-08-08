<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Default Admin User
        User::updateOrCreate(
            ['email' => 'admin@pos.com'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('password123'),
                'role' => UserRole::ADMIN,
                'email_verified_at' => now(),
            ]
        );

        // 2. Create Default Staff User
        User::updateOrCreate(
            ['email' => 'staff@pos.com'],
            [
                'name' => 'POS Sales Staff',
                'password' => Hash::make('password123'),
                'role' => UserRole::STAFF,
                'email_verified_at' => now(),
            ]
        );
    }
}