<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::insert([
            [
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'), // selalu hash password!
                'phone' => '081234567890',
                'institution' => 'Universitas Contoh',
                'role' => 'admin',
                'coordinator_name' => 'Koordinator Admin',
                'is_active' => 1
            ],
            [
                'name' => 'Mitra User',
                'email' => 'mitra@example.com',
                'password' => Hash::make('password'),
                'phone' => '081234567891',
                'institution' => 'Lembaga Mitra',
                'role' => 'mitra',
                'coordinator_name' => 'Koordinator Mitra',
                'is_active' => 1
            ],
            [
                'name' => 'Teknisi Lab',
                'email' => 'teknisi@example.com',
                'password' => Hash::make('password'),
                'phone' => '081234567892',
                'institution' => 'Lab Contoh',
                'role' => 'teknisi_lab',
                'coordinator_name' => 'Koordinator Teknisi',
                'is_active' => 1
            ],
            [
                'name' => 'Tenaga Ahli',
                'email' => 'tenaga@example.com',
                'password' => Hash::make('password'),
                'phone' => '081234567893',
                'institution' => 'Konsultan Ahli',
                'role' => 'tenaga_ahli',
                'coordinator_name' => 'Koordinator Ahli',
                'is_active' => 1
            ]
        ]);
    }
}
