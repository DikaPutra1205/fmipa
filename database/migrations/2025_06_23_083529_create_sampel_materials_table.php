<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sampel_materials', function (Blueprint $table) {
            $table->id();
            $table->string('nama_sampel_material'); // Nama Sampel & Material
            $table->integer('jumlah_sampel');       // Jumlah Sampel
            $table->date('tanggal_penerimaan');    // Tanggal Penerimaan
            $table->date('tanggal_pengembalian')->nullable(); // Tanggal Pengembalian (bisa kosong)
            $table->boolean('status_data')->default(true); // Status Data (Aktif/Tidak Aktif)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sampel_materials');
    }
};