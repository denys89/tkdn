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
        // Create or update super admin user
        User::updateOrCreate(
            ['email' => 'superadmin@tkdn.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'role' => 'super_admin',
            ]
        );

        // Create or update admin user
        User::updateOrCreate(
            ['email' => 'admin@tkdn.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // Create or update operator user
        User::updateOrCreate(
            ['email' => 'operator@tkdn.com'],
            [
                'name' => 'Operator',
                'password' => Hash::make('password'),
                'role' => 'operator',
            ]
        );

        // Update existing users to have operator role if they don't have one
        User::whereNull('role')->orWhere('role', '')->update(['role' => 'operator']);
    }
}
