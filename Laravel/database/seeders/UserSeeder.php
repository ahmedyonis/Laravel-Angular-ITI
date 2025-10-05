<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@theatre.com',
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);

        // مستخدم عادي
        User::create([
            'name' => 'Ahmed',
            'email' => 'ahmed@user.com',
            'password' => bcrypt('password'),
            'role' => 'user'
        ]);
    }
}
