<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\AlatBahan;

class AlatBahanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AlatBahan::insert([
            [
                'nama_alat_bbahan' => 'Spektrofotometer UV-Vis',
                'kondisi_alat' => 'Sangat Baik',
                'jumlah_alat' => 5,
                'status_data' => true, // true untuk Aktif
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_alat_bbahan' => 'Mikroskop Binokuler',
                'kondisi_alat' => 'Baik',
                'jumlah_alat' => 12,
                'status_data' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_alat_bbahan' => 'Oven Laboratorium',
                'kondisi_alat' => 'Rusak Ringan',
                'jumlah_alat' => 2,
                'status_data' => false, // false untuk Tidak Aktif
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_alat_bbahan' => 'Pipet Mikro 1000 µl',
                'kondisi_alat' => 'Sangat Baik',
                'jumlah_alat' => 20,
                'status_data' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_alat_bbahan' => 'pH Meter Digital',
                'kondisi_alat' => 'Perlu Kalibrasi',
                'jumlah_alat' => 3,
                'status_data' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_alat_bbahan' => 'Tabung Reaksi (pack)',
                'kondisi_alat' => 'Baru',
                'jumlah_alat' => 50,
                'status_data' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
