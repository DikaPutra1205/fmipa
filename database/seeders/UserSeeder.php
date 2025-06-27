<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash; // [PERUBAHAN] Import Hash

class UserSeeder extends Seeder {
    public function run(): void {
        DB::table('users')->insert([
            // [PERUBAHAN] Semua password sekarang menggunakan Hash::make()
            ['id' => 1, 'name' => 'Teknisi Lab', 'email' => 'teknisilab@example.com', 'password' => Hash::make('password'), 'phone' => '081234567890', 'institution' => null, 'role' => 'teknisi_lab', 'coordinator_name' => 'Koordinator 1', 'is_active' => 1],
            ['id' => 2, 'name' => 'Tenaga Ahli', 'email' => 'tenagaahli@example.com', 'password' => Hash::make('password'), 'phone' => '081234567891', 'institution' => null, 'role' => 'tenaga_ahli', 'coordinator_name' => 'Koordinator 2', 'is_active' => 1],
            ['id' => 3, 'name' => 'Mitra Uji', 'email' => 'pnj@example.com', 'password' => Hash::make('password'), 'phone' => '081234567892', 'institution' => 'Politeknik Negeri Jakarta', 'role' => 'mitra', 'coordinator_name' => 'mitra@example.com', 'is_active' => 1],
            ['id' => 4, 'name' => 'Admin User', 'email' => 'admin@example.com', 'password' => Hash::make('password'), 'phone' => '081234567890', 'institution' => 'Universitas Contoh', 'role' => 'admin', 'coordinator_name' => 'Koordinator Admin', 'is_active' => 1],
            ['id' => 5, 'name' => 'Mitra User', 'email' => 'mitra@example.com', 'password' => Hash::make('password'), 'phone' => '081234567891', 'institution' => 'Lembaga Mitra', 'role' => 'mitra', 'coordinator_name' => 'Koordinator Mitra', 'is_active' => 1],
            ['id' => 6, 'name' => 'Teknisi Lab', 'email' => 'teknisi@example.com', 'password' => Hash::make('password'), 'phone' => '081234567892', 'institution' => 'Lab Contoh', 'role' => 'teknisi_lab', 'coordinator_name' => 'Koordinator Teknisi', 'is_active' => 1],
            ['id' => 7, 'name' => 'Tenaga Ahli', 'email' => 'tenaga@example.com', 'password' => Hash::make('password'), 'phone' => '081234567893', 'institution' => 'Konsultan Ahli', 'role' => 'tenaga_ahli', 'coordinator_name' => 'Koordinator Ahli', 'is_active' => 1],
            ['id' => 9, 'name' => 'Adeline', 'email' => 'adeline@example.com', 'password' => Hash::make('password'), 'phone' => '125689', 'institution' => null, 'role' => 'tenaga_ahli', 'coordinator_name' => 'Dika', 'is_active' => 1],
            ['id' => 10, 'name' => 'tes', 'email' => 'tes@example.co.id', 'password' => Hash::make('password'), 'phone' => '1234567890', 'institution' => 'tes', 'role' => 'mitra', 'coordinator_name' => null, 'is_active' => 1],
        ]);
    }
}
