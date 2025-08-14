<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            [
                'name'         => 'Admin',
                'email'        => 'admin@example.com',
                'password'     => Hash::make('password123'),
                'role'         => 'admin',
                'phone_number' => '081234567890',
                'photo'        => null,
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'name'         => 'User Satu',
                'email'        => 'user1@example.com',
                'password'     => Hash::make('password123'),
                'role'         => 'user',
                'phone_number' => '081111111111',
                'photo'        => null,
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'name'         => 'User Dua',
                'email'        => 'user2@example.com',
                'password'     => Hash::make('password123'),
                'role'         => 'user',
                'phone_number' => '082222222222',
                'photo'        => null,
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
        ]);
    }
}
