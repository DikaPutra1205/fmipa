<?php

namespace App\Providers;

use App\Models\Test;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Test' => 'App\Policies\TestPolicy', // Pastikan ini dikomentari atau dihapus
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // =====================================================================
        // SOLUSI FINAL: Gunakan Gate::before untuk memberikan hak akses super ke admin
        // =====================================================================
        Gate::before(function (User $user, string $ability) {
            // Jika user adalah admin, izinkan semua aksi apa pun.
            // Ini adalah cara paling ampuh untuk memberikan hak akses super.
            if ($user->role === 'admin') {
                return true;
            }
        });

        // =====================================================================
        // DEFINISI GATE SPESIFIK UNTUK ROLE LAIN
        // =====================================================================
        
        // Gate untuk melihat detail sebuah order pengujian
        Gate::define('view', function (User $user, Test $test) {
            // Karena admin sudah di-handle oleh Gate::before, kita hanya perlu cek role lain
            if (in_array($user->role, ['teknisi_lab', 'tenaga_ahli'])) {
                return true;
            }
            // Mitra hanya bisa melihat order miliknya sendiri.
            return $user->id === $test->user_id;
        });

        // Gate untuk menyetujui/menolak pengajuan awal
        Gate::define('approve', function (User $user, Test $test) {
            return $user->role === 'teknisi_lab';
        });

        // Gate untuk Mitra mengupdate (mengisi detail sampel)
        Gate::define('update', function (User $user, Test $test) {
            return $user->id === $test->user_id;
        });

        // Gate untuk Teknisi mengonfirmasi penerimaan sampel
        Gate::define('confirmSample', function (User $user, Test $test) {
            if ($test->assigned_teknisi_id) {
                return $user->id === $test->assigned_teknisi_id;
            }
            return $user->role === 'teknisi_lab';
        });

        // Gate untuk Teknisi mengunggah hasil
        Gate::define('uploadResult', function (User $user, Test $test) {
            return $user->id === $test->assigned_teknisi_id;
        });

        // Gate untuk Tenaga Ahli memverifikasi hasil
        Gate::define('verifyResult', function (User $user, Test $test) {
            return $user->role === 'tenaga_ahli';
        });

        // Gate untuk Mitra menyelesaikan order
        Gate::define('completeOrder', function (User $user, Test $test) {
            return $user->id === $test->user_id;
        });

        // Gate 'isAdmin' dari file Anda yang lain. Berguna untuk pengecekan umum.
        Gate::define('isAdmin', function ($user) {
            return $user->role === 'admin';
        });
    }
}
