<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AlatBahanSeeder extends Seeder {
    public function run(): void {
        DB::table('alat_bahans')->insert([
            ['id' => 1, 'nama_alat_bahan' => 'Spektrofotometer UV-Vis', 'kondisi_alat' => 'Sangat Baik', 'jumlah_alat' => 5, 'status_data' => 1],
            ['id' => 2, 'nama_alat_bahan' => 'Mikroskop Binokuler', 'kondisi_alat' => 'Baik', 'jumlah_alat' => 12, 'status_data' => 1],
            ['id' => 3, 'nama_alat_bahan' => 'Oven Laboratorium', 'kondisi_alat' => 'Rusak Ringan', 'jumlah_alat' => 2, 'status_data' => 0],
            ['id' => 4, 'nama_alat_bahan' => 'Pipet Mikro 1000 µl', 'kondisi_alat' => 'Sangat Baik', 'jumlah_alat' => 20, 'status_data' => 1],
            ['id' => 5, 'nama_alat_bahan' => 'pH Meter Digital', 'kondisi_alat' => 'Perlu Kalibrasi', 'jumlah_alat' => 3, 'status_data' => 1],
            ['id' => 6, 'nama_alat_bahan' => 'Tabung Reaksi (pack)', 'kondisi_alat' => 'Baru', 'jumlah_alat' => 50, 'status_data' => 1],
        ]);
    }
}
