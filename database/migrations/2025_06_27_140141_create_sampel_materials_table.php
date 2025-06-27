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
            $table->foreignId('test_id')->nullable()->constrained('tests')->onDelete('cascade');
            $table->enum('status', ['menunggu_kedatangan', 'diterima_di_lab', 'sedang_diuji', 'pengujian_selesai', 'selesai']);
            $table->string('nama_sampel_material');
            $table->integer('jumlah_sampel');
            $table->date('tanggal_penerimaan')->nullable();
            $table->date('tanggal_pengembalian')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('sampel_materials');
    }
};
