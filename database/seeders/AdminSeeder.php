<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('admin'), 
                'role' => 'admin',
                'department' => 'IT',
            ]
        );

        User::updateOrCreate(
            ['email' => 'it1@gmail.com'],
            [
                'name' => 'IT Spec 1',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'department' => 'IT',
            ]
        );

        User::updateOrCreate(
            ['email' => 'it2@gmail.com'],
            [
                'name' => 'IT Spec 2',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'department' => 'IT',
            ]
        );
    }
}
