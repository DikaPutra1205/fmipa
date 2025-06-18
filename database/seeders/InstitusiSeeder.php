<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InstitusiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [];

        for ($i = 1; $i <= 10; $i++) {
            $data[] = [
                'nama_institusi'   => 'PT Astra Honda' . $i,
                'nama_koordinator' => 'Aryajaya Alamsyah' . $i,
                'telp_wa'          => '08' . rand(1111111111, 9999999999),
                'status'           => rand(0, 1), // 1: Aktif, 0: Tidak Aktif
                'created_at'       => now(),
                'updated_at'       => now(),
            ];
        }
        DB::table('institusi')->insert($data);
    }
}
