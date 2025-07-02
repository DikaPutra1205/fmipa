<?php
// File: app/Http/Controllers/DashboardController.php

namespace App\Http\Controllers;

use App\Models\AlatBahan;
use App\Models\Module;
use App\Models\Test;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard yang sesuai dengan role pengguna.
     */
    public function index(): View
    {
        $user = Auth::user();

        // Jika yang login adalah Mitra, tampilkan dashboard khusus Mitra
        if ($user->role === 'mitra') {
            $modules = Module::all();
            return view('dashboard.mitra', compact('modules'));
        }

        // Jika bukan Mitra (Admin, Teknisi, dll.), tampilkan dashboard utama
        return $this->showAdminDashboard();
    }

    /**
     * Menyiapkan data dan menampilkan dashboard untuk Admin/Teknisi/Ahli.
     */
    private function showAdminDashboard(): View
    {
        // 1. Ambil data statistik umum
        $data['totalTeknisi'] = User::where('role', 'teknisi_lab')->count();
        $data['totalAhli'] = User::where('role', 'tenaga_ahli')->count();
        $data['totalMitra'] = User::where('role', 'mitra')->count();
        $data['totalAlat'] = AlatBahan::count();

        // 2. Ambil data keuangan
        $data['pendapatanBulanIni'] = Test::whereIn('status', ['pembayaran_dikonfirmasi', 'selesai'])
                                         ->whereMonth('updated_at', now()->month)
                                         ->whereYear('updated_at', now()->year)
                                         ->sum('final_price');

        $data['pendapatanTahunIni'] = Test::whereIn('status', ['pembayaran_dikonfirmasi', 'selesai'])
                                         ->whereYear('updated_at', now()->year)
                                         ->sum('final_price');

        // 3. Ambil data pengujian yang sedang berjalan (5 terbaru)
        $data['ongoingTests'] = Test::with(['mitra', 'module'])
                                    ->whereNotIn('status', ['selesai', 'ditolak'])
                                    ->latest('updated_at')
                                    ->take(5)
                                    ->get();
        
        // 4. Definisikan persentase progres untuk setiap status
        $data['statusProgress'] = [
            'menunggu_persetujuan_awal' => 10,
            'menunggu_detail_sampel' => 20,
            'menunggu_penerimaan_sampel' => 30,
            'pengujian_berjalan' => 50,
            'revisi_diperlukan' => 55,
            'menunggu_verifikasi_ahli' => 75,
            'menunggu_pembayaran' => 90,
            'pembayaran_dikonfirmasi' => 95,
        ];

        return view('dashboard.admin', $data);
    }
}