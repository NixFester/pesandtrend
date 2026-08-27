<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin Account
        User::firstOrCreate(
            ['email' => 'admin@pesantrends.id'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin Utama',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
            ]
        );

        // Demo User Account
        User::firstOrCreate(
            ['email' => 'user@pesantrends.id'],
            [
                'name' => 'User Demo',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // Additional sample users
        User::factory()->count(5)->create();
    }
}
