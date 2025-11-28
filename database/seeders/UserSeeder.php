<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user if not exists
        User::firstOrCreate(
            ['email' => 'admin@demo.com'],
            [
                'name' => 'مدير النظام',
                'password' => Hash::make('123123123'),
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        // Create additional test users
        $users = [
            [
                'name' => 'أحمد محمد',
                'email' => 'ahmed@demo.com',
                'password' => Hash::make('123123123'),
                'role' => 'user',
                'is_active' => true,
            ],
            [
                'name' => 'فاطمة علي',
                'email' => 'fatima@demo.com',
                'password' => Hash::make('123123123'),
                'role' => 'user',
                'is_active' => true,
            ],
            [
                'name' => 'محمد عبدالله',
                'email' => 'mohammed@demo.com',
                'password' => Hash::make('123123123'),
                'role' => 'user',
                'is_active' => true,
            ],
            [
                'name' => 'سارة أحمد',
                'email' => 'sara@demo.com',
                'password' => Hash::make('123123123'),
                'role' => 'admin',
                'is_active' => true,
            ],
            [
                'name' => 'خالد العتيبي',
                'email' => 'khaled@demo.com',
                'password' => Hash::make('123123123'),
                'role' => 'user',
                'is_active' => true,
            ],
        ];

        foreach ($users as $userData) {
            User::firstOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }
    }
}
