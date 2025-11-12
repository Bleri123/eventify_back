<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'first_name' => 'Bleon',
                'last_name' => 'Nasufi',
                'email' => 'bleonnasufi9@gmail.com',
                'password_hash' => Hash::make('password123'),
                'role' => 'admin',
                'phone_number' => '+38345231491',
                'city' => 'Vushtrri',
                'address' => 'Vushtrri, Kosovo',
                'gender' => 'male',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'Edi',
                'last_name' => 'Pajaziti',
                'email' => 'edipajaziti5@gmail.com',
                'password_hash' => Hash::make('password123'),
                'role' => 'user',
                'phone_number' => '+38345123456',
                'city' => 'Vushtrri',
                'address' => 'Vushtrri, Kosovo',
                'gender' => 'male',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'Elton',
                'last_name' => 'Rexha',
                'email' => 'eltonrexha20@gmail.com',
                'password_hash' => Hash::make('password123'),
                'role' => 'admin',
                'phone_number' => '+38345111111',
                'city' => 'Vushtrri',
                'address' => 'Vushtrri, Kosovo',
                'gender' => 'male',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'Talat',
                'last_name' => 'Mustafa',
                'email' => 'talatmustafa476@gmail.com',
                'password_hash' => Hash::make('password123'),
                'role' => 'user',
                'phone_number' => '+38345111111',
                'city' => 'Vushtrri',
                'address' => 'Vushtrri, Kosovo',
                'gender' => 'male',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'Jon',
                'last_name' => 'Hajredinaj',
                'email' => 'jonhajredinaj@gmail.com',
                'password_hash' => Hash::make('password123'),
                'role' => 'user',
                'phone_number' => '+38345111111',
                'city' => 'Vushtrri',
                'address' => 'Vushtrri, Kosovo',
                'gender' => 'male',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'Dijar',
                'last_name' => 'Qerkezi',
                'email' => 'dijarqerkezi@gmail.com',
                'password_hash' => Hash::make('password123'),
                'role' => 'user',
                'phone_number' => '+38345111111',
                'city' => 'Vushtrri',
                'address' => 'Vushtrri, Kosovo',
                'gender' => 'male',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('users')->insert($users);
    }
}