<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SampelMaterial; // Import model SampelMaterial
use Carbon\Carbon; // Untuk memudahkan manipulasi tanggal

class SampelMaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Opsional: Hapus data yang sudah ada sebelum mengisi yang baru
        SampelMaterial::truncate(); // Membutuhkan $fillable atau $guarded di model

        $dataSampelMaterial = [
            [
                'nama_sampel_material' => 'Aluminium dan Logam',
                'jumlah_sampel' => 10,
                'tanggal_penerimaan' => Carbon::create(2025, 5, 25),
                'tanggal_pengembalian' => Carbon::create(2025, 5, 30),
                'status_data' => true, // Aktif
            ],
            [
                'nama_sampel_material' => 'Polimer & Plastik',
                'jumlah_sampel' => 10,
                'tanggal_penerimaan' => Carbon::create(2025, 5, 25),
                'tanggal_pengembalian' => Carbon::create(2025, 5, 30),
                'status_data' => true,
            ],
            [
                'nama_sampel_material' => 'Keramik & Refraktori',
                'jumlah_sampel' => 10,
                'tanggal_penerimaan' => Carbon::create(2025, 5, 25),
                'tanggal_pengembalian' => Carbon::create(2025, 5, 30),
                'status_data' => true,
            ],
            [
                'nama_sampel_material' => 'Komposit',
                'jumlah_sampel' => 10,
                'tanggal_penerimaan' => Carbon::create(2025, 5, 25),
                'tanggal_pengembalian' => Carbon::create(2025, 5, 30),
                'status_data' => true,
            ],
            [
                'nama_sampel_material' => 'Nanomaterial',
                'jumlah_sampel' => 10,
                'tanggal_penerimaan' => Carbon::create(2025, 5, 25),
                'tanggal_pengembalian' => Carbon::create(2025, 5, 30),
                'status_data' => true,
            ],
            [
                'nama_sampel_material' => 'Biomaterial',
                'jumlah_sampel' => 10,
                'tanggal_penerimaan' => Carbon::create(2025, 5, 25),
                'tanggal_pengembalian' => Carbon::create(2025, 5, 30),
                'status_data' => true,
            ],
            [
                'nama_sampel_material' => 'Kaca & Material Amorf',
                'jumlah_sampel' => 10,
                'tanggal_penerimaan' => Carbon::create(2025, 5, 25),
                'tanggal_pengembalian' => Carbon::create(2025, 5, 30),
                'status_data' => true,
            ],
            [
                'nama_sampel_material' => 'Material X (Tidak Aktif)',
                'jumlah_sampel' => 5,
                'tanggal_penerimaan' => Carbon::create(2025, 5, 20),
                'tanggal_pengembalian' => null, // Contoh: belum dikembalikan
                'status_data' => false, // Tidak Aktif
            ],
        ];

        // Masukkan data ke database
        foreach ($dataSampelMaterial as $data) {
            SampelMaterial::create($data);
        }

        $this->command->info('Data Sampel & Material berhasil di-seed!');
    }
}