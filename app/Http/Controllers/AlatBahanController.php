<?php

namespace App\Http\Controllers;

use App\Models\AlatBahan; // Import model AlatBahan
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Jika perlu filter berdasarkan user role
use Yajra\DataTables\Facades\DataTables;

class AlatBahanController extends Controller
{
    // Method untuk menampilkan view halaman alat dan bahan
    public function index()
    {
        return view('alat_bahan.dashboard');
    }

    // Method untuk mengambil data alat dan bahan untuk DataTables
    public function getData(Request $request)
    {
        $data = AlatBahan::query();

        // Anda bisa menambahkan filter atau kondisi khusus di sini jika diperlukan.
        // Contoh: $data->where('status_data', true);

        return DataTables::of($data)
            ->addIndexColumn() // Untuk kolom 'No'
            ->editColumn('status_data', function ($row) {
                // Konversi boolean ke string 'Aktif' atau 'Tidak Aktif'
                return $row->status_data ? 'Aktif' : 'Tidak Aktif';
            })
            // Tambahkan kolom aksi jika diperlukan (misal: edit, delete)
            ->addColumn('aksi', function ($row) {
                $editUrl = url('/alat-bahan/' . $row->id . '/edit');
                $deleteUrl = url('/alat-bahan/' . $row->id);
                $user = Auth::user(); // Dapatkan user yang sedang login

                $buttons = '';
                if ($user && $user->role === 'admin') { // Hanya admin yang bisa edit/delete
                    $buttons .= '
                        <a href="' . $editUrl . '" class="btn btn-sm btn-icon btn-primary me-1" title="Edit">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-pencil" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M15 6l3 3l-9 9h-3v-3z" />
                                <path d="M18 3l3 3" />
                            </svg>
                        </a>
                        <button class="btn btn-sm btn-icon btn-danger btn-delete-alat-bahan" data-id="' . $row->id . '" title="Hapus">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M4 7l16 0" />
                                <path d="M10 11l0 6" />
                                <path d="M14 11l0 6" />
                                <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                <path d="M9 7l0 -3h6l0 3" />
                            </svg>
                        </button>
                    ';
                }
                return $buttons;
            })
            ->rawColumns(['aksi']) // Penting agar HTML di kolom 'aksi' dirender
            ->make(true);
    }

    // Method untuk menampilkan form edit
    public function edit($id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        $alatBahan = AlatBahan::findOrFail($id);
        return view('alat_bahan.edit', compact('alatBahan'));
    }

    // Method untuk menghapus data
    public function destroy($id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        $alatBahan = AlatBahan::findOrFail($id);
        $alatBahan->delete();
        return response()->json(['success' => true, 'message' => 'Data alat & bahan berhasil dihapus']);
    }
}