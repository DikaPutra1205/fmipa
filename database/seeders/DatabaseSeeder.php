<?php
// File: database/seeders/DatabaseSeeder.php
namespace Database\Seeders;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            ServiceSeeder::class,
            TestOrderSeeder::class,
            AlatBahanSeeder::class,
        ]);
    }
}