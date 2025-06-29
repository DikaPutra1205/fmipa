<?php
// File: app/Http/Controllers/HasilUjiController.php

namespace App\Http\Controllers;

use App\Models\Test;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class HasilUjiController extends Controller
{
    /**
     * Menampilkan halaman utama modul Hasil Uji.
     */
    public function index(): View
    {
        return view('hasil_uji.index');
    }

    /**
     * Menyediakan data untuk DataTables.
     */
    public function data(Request $request)
    {
        $user = Auth::user();

        // Ambil data pengujian yang statusnya sudah memungkinkan untuk download hasil
        $completedStatuses = ['pembayaran_dikonfirmasi', 'selesai'];
        
        $query = Test::with(['mitra', 'module', 'testPackage'])
                     ->whereIn('status', $completedStatuses);

        // Jika yang login adalah Mitra, hanya tampilkan hasil miliknya
        if ($user->role === 'mitra') {
            $query->where('user_id', $user->id);
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('module_name', function ($row) {
                return $row->module->name ?? 'N/A';
            })
            ->addColumn('package_name', function ($row) {
                return $row->testPackage->name ?? 'N/A';
            })
            ->addColumn('completion_date', function ($row) {
                // Menggunakan updated_at sebagai tanggal penyelesaian
                return $row->updated_at->format('d M Y');
            })
            ->addColumn('mitra_name', function ($row) {
                return $row->mitra->name ?? 'N/A';
            })
            ->addColumn('aksi', function ($row) {
                // Tombol untuk download file hasil
                if ($row->result_file_path) {
                    $downloadUrl = asset('storage/' . $row->result_file_path);
                    return '
                        <a href="' . $downloadUrl . '" target="_blank" class="btn btn-sm btn-success">
                            <i class="ti ti-download me-1"></i> Download Hasil
                        </a>
                    ';
                }
                return 'File tidak tersedia';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }
}