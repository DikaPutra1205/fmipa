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
        Schema::create('alat_bahans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_alat_bahan');
            $table->string('kondisi_alat');
            $table->integer('jumlah_alat');
            $table->boolean('status_data')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('alat_bahans');
    }
};
