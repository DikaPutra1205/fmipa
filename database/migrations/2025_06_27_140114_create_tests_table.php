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
        Schema::create('tests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('module_id');
            $table->foreignId('test_package_id')->constrained('test_packages');
            $table->unsignedInteger('quantity')->nullable();
            $table->decimal('final_price', 15, 2)->nullable();
            $table->foreignId('assigned_teknisi_id')->nullable()->constrained('users');
            $table->foreignId('verified_by_ahli_id')->nullable()->constrained('users');
            $table->text('note')->nullable();
            $table->enum('status', ['menunggu_persetujuan_awal', 'menunggu_detail_sampel', 'menunggu_penerimaan_sampel', 'pengujian_berjalan', 'menunggu_verifikasi_ahli', 'revisi_diperlukan', 'menunggu_pembayaran', 'pembayaran_dikonfirmasi', 'selesai', 'ditolak']);
            $table->string('result_file_path')->nullable();
            $table->text('rejection_notes')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('tests');
    }
};
