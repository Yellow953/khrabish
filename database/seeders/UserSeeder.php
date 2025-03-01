<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'admin',
                'phone' => '96170285659',
                'role' => 'admin',
                'email' => 'test@admin.com',
                'image' => 'assets/images/default_profile.png',
                'password' => Hash::make('qwe123'),
                'currency_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'user',
                'phone' => '96170285659',
                'role' => 'user',
                'email' => 'test@test.com',
                'image' => 'assets/images/default_profile.png',
                'password' => Hash::make('qwe123'),
                'currency_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
