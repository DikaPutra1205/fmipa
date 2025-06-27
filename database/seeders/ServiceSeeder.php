<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceSeeder extends Seeder {
    public function run(): void {
        // Data Modul
        DB::table('modules')->insert([
            ['id' => 1, 'code' => 'XRD', 'name' => 'X-Ray Diffraction', 'description' => 'Analisis kristal dengan XRD', 'details' => '{"alat":"Malvern Panalytical Empyrean Diffractometer","metode":"Flat Sample, SAXS, In-Situ","deskripsi_lengkap":"Identifikasi fasa kristalin, kristalinitas, dan parameter kisi.","jenis_sampel":[{"tipe":"Powder","spek":"0,2 gram, 150–200 mesh"},{"tipe":"Padatan","spek":"Min. 1,5 cm × 1,5 cm, permukaan rata"},{"tipe":"Gel","spek":"0.2 gram"}]}'],
            ['id' => 2, 'code' => 'XRF', 'name' => 'X-Ray Fluorescence', 'description' => 'Deteksi unsur dengan XRF', 'details' => '{"alat":"Malvern Panalytical Epsilon 1","metode":"Omnian, Omnian High","deskripsi_lengkap":"Analisis unsur dari Na hingga U secara kuantitatif/semi-kuantitatif.","jenis_sampel":[{"tipe":"Powder kering","spek":"<200 mesh"},{"tipe":"Padatan","spek":"min. 2 cm × 2 cm"},{"tipe":"Pellet diameter","spek":"3–4 cm (jika tersedia)"},{"tipe":"Cair atau Liquid","spek":"10 ml"}]}'],
            ['id' => 3, 'code' => 'SEM-EDX', 'name' => 'SEM-EDX', 'description' => 'Pengamatan permukaan dan komposisi', 'details' => '{"alat":"Thermo Scientific Quattro","metode":"Secondary Electron, Back-Scattered Electron","deskripsi_lengkap":"Karakterisasi morfologi permukaan dan komposisi unsur skala mikro.","jenis_sampel":[{"tipe":"Padatan kering","spek":"konduktif/non-konduktif"},{"tipe":"Plate","spek":"Ukuran maks. 1 cm × 1 cm × 0,5 cm"},{"tipe":"Kertas","spek":"Ukuran maks 1cm x 1 cm"},{"tipe":"Gel","spek":"minimal 0.2 gram"},{"tipe":"Pelapisan","spek":"Au/Pt untuk sampel non-konduktif"}]}'],
            ['id' => 4, 'code' => 'UV-VIS', 'name' => 'UV-VIS Spectrophotometry', 'description' => 'Spektrofotometri UV-VIS', 'details' => '{"alat":"Agilent Cary 100 UV-Vis","metode":"Transmittansi, Absorbansi","deskripsi_lengkap":"Analisis transmittansi, absorbansi, band gap, dan konsentrasi.","jenis_sampel":[{"tipe":"Larutan","spek":"min. 2 mL"},{"tipe":"Film/padatan transparan","spek":"maks. 1 cm × 1 cm"}]}'],
            ['id' => 5, 'code' => 'FTIR', 'name' => 'Fourier-Transform Infrared', 'description' => 'Spektrum inframerah', 'details' => '{"alat":"FTIR Spectrometer (QATR & DRS modules)","metode":"ATR, Transmitance","deskripsi_lengkap":"Identifikasi gugus fungsi senyawa organik/anorganik.","jenis_sampel":[{"tipe":"Powder","spek":"dicampur KBr atau pellet"},{"tipe":"Padatan kecil","spek":"dan bersih (<1 cm)"},{"tipe":"Larutan","spek":"min. 1 mL, dengan ATR"}]}'],
        ]);

        // Data Paket Tes
        DB::table('test_packages')->insert([
            ['id' => 1, 'module_id' => 1, 'name' => 'Pengujian Saja', 'price' => 250000],
            ['id' => 2, 'module_id' => 1, 'name' => 'Pengujian + Analisis', 'price' => 850000],
            ['id' => 3, 'module_id' => 2, 'name' => 'Pengujian Saja', 'price' => 200000],
            ['id' => 4, 'module_id' => 3, 'name' => 'SEM Saja', 'price' => 650000],
            ['id' => 5, 'module_id' => 3, 'name' => 'SEM + EDX', 'price' => 900000],
            ['id' => 6, 'module_id' => 4, 'name' => 'Pengujian', 'price' => 150000],
            ['id' => 7, 'module_id' => 5, 'name' => 'Pengujian', 'price' => 150000],
        ]);
    }
}