<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@blog.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => 'active',
        ]);

        User::create([
            'name' => 'Editor User',
            'email' => 'editor@blog.com',
            'password' => Hash::make('password'),
            'role' => 'editor',
            'status' => 'active',
        ]);

        User::create([
            'name' => 'Author User',
            'email' => 'author@blog.com',
            'password' => Hash::make('password'),
            'role' => 'author',
            'status' => 'active',
        ]);
    }
}