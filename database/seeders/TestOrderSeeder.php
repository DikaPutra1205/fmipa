<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TestOrderSeeder extends Seeder {
    public function run(): void {
        DB::table('tests')->insert([
            ['id' => 1, 'user_id' => 5, 'module_id' => 1, 'test_package_id' => 2, 'quantity' => 2, 'final_price' => 1700000.00, 'assigned_teknisi_id' => 6, 'verified_by_ahli_id' => 7, 'note' => null, 'status' => 'selesai', 'result_file_path' => 'test_results/gwy7FJsXql4odnXoRgTUZbf5YyuuG8Os02OigCff.pdf', 'rejection_notes' => 'tes', 'created_at' => '2025-06-26 09:22:21', 'updated_at' => '2025-06-26 10:35:23']
        ]);

        DB::table('sampel_materials')->insert([
            ['id' => 1, 'test_id' => 1, 'status' => 'selesai', 'nama_sampel_material' => 'abclimadasar', 'jumlah_sampel' => 2, 'tanggal_penerimaan' => '2025-06-26', 'tanggal_pengembalian' => null, 'created_at' => '2025-06-26 10:19:12', 'updated_at' => '2025-06-26 10:35:23'],
            ['id' => 2, 'test_id' => null, 'status' => 'diterima_di_lab', 'nama_sampel_material' => 'abc', 'jumlah_sampel' => 5, 'tanggal_penerimaan' => '2025-06-25', 'tanggal_pengembalian' => null, 'created_at' => '2025-06-26 23:19:53', 'updated_at' => '2025-06-26 23:35:32']
        ]);
    }
}