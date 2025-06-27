<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\Test;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class ModuleController extends Controller
{
    /**
     * Menampilkan halaman dashboard untuk sebuah modul spesifik.
     * Ini akan dipanggil oleh route: /modul/{module:code}
     */
    public function show(Module $module): View
    {
        // Method ini hanya bertugas menampilkan view utama,
        // dan mengirimkan data modul yang sedang dibuka.
        // Data untuk tabel akan diambil via AJAX oleh method data().
        return view('modules.show', [
            'module' => $module,
        ]);
    }

    /**
     * Menyediakan data pengujian untuk DataTables secara asynchronous (AJAX).
     * Ini diadaptasi dari method getData() yang Anda berikan.
     */
    public function data(Module $module, Request $request)
    {
        // Mulai query pada model Test, dan langsung ambil data relasi 'mitra'
        // untuk menghindari N+1 problem.
        $query = Test::with('mitra')->where('module_id', $module->id);

        // Filter berdasarkan role pengguna
        $user = Auth::user();
        if ($user->role === 'mitra') {
            // Jika yang login adalah Mitra, hanya tampilkan pengujian miliknya
            $query->where('user_id', $user->id);
        }
        // Jika Admin, Teknisi, atau Tenaga Ahli, mereka bisa melihat semua.

        return DataTables::of($query)
            ->addIndexColumn() // Kolom 'No'
            ->editColumn('status', function ($row) {
                // Memberi warna pada status agar lebih mudah dibaca
                $badges = [
                    'menunggu_persetujuan_awal' => 'bg-label-secondary',
                    'menunggu_detail_sampel' => 'bg-label-info',
                    'menunggu_penerimaan_sampel' => 'bg-label-info',
                    'pengujian_berjalan' => 'bg-label-primary',
                    'menunggu_verifikasi_ahli' => 'bg-label-warning',
                    'revisi_diperlukan' => 'bg-label-danger',
                    'menunggu_pembayaran' => 'bg-label-success',
                    'pembayaran_dikonfirmasi' => 'bg-label-success',
                    'selesai' => 'bg-label-dark',
                    'ditolak' => 'bg-label-danger',
                ];
                $badgeClass = $badges[$row->status] ?? 'bg-label-secondary';
                $statusText = ucwords(str_replace('_', ' ', $row->status));
                return '<span class="badge ' . $badgeClass . '">' . $statusText . '</span>';
            })
            ->addColumn('mitra_name', function ($row) {
                // Mengambil nama dari relasi 'mitra'
                return $row->mitra->name ?? 'N/A';
            })
            ->addColumn('tanggal_pengajuan', function ($row) {
                return $row->created_at->format('d M Y');
            })
            ->addColumn('aksi', function ($row) {
                // Ini adalah "Tombol Cerdas" kita
                $url = route('wizard.dispatcher', $row->id);
                return '<a href="' . $url . '" class="btn btn-sm btn-primary">Lihat Detail</a>';
            })
            ->rawColumns(['status', 'aksi']) // Penting agar HTML di kolom status & aksi dirender
            ->make(true);
    }
}
