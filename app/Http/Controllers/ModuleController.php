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
    public function index()
    {
        return view('module.dashboard');
    }

    public function getData(Request $request)
    {
        $data = Module::query();
        $user = Auth::user();

        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('details', function ($row) {
                $details = is_array($row->details) ? $row->details : json_decode($row->details, true);
                if (!is_array($details)) return '-';

                $output = '<div class="detail-cell">';
                $output .= "<strong>Alat:</strong> " . ($details['alat'] ?? '-') . "<br>";
                $output .= "<strong>Metode:</strong> " . ($details['metode'] ?? '-') . "<br>";
                $output .= "<strong>Deskripsi:</strong> " . ($details['deskripsi_lengkap'] ?? '-') . "<br>";

                if (!empty($details['jenis_sampel']) && is_array($details['jenis_sampel'])) {
                    $output .= "<strong>Jenis Sampel:</strong><ul>";
                    foreach ($details['jenis_sampel'] as $sampel) {
                        $tipe = $sampel['tipe'] ?? '-';
                        $spek = $sampel['spek'] ?? '-';
                        $output .= "<li><strong>$tipe</strong>: $spek</li>";
                    }
                    $output .= "</ul>";
                }
                $output .= '</div>';

                return $output;
            })
            ->rawColumns(['aksi', 'details'])
            ->addColumn('aksi', function ($row) {
                $editUrl = url('/module/' . $row->id . '/edit');
                return '
                <a href="' . $editUrl . '" class="btn btn-sm btn-icon btn-primary me-1" title="Edit">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-pencil" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M15 6l3 3l-9 9h-3v-3z" />
                        <path d="M18 3l3 3" />
                    </svg>
                </a>
                <button class="btn btn-sm btn-icon btn-danger btn-delete-module" data-id="' . $row->id . '" title="Hapus">
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
            })
            ->make(true);
    }

    public function create()
    {
        return view('module.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'details.alat' => 'required|string',
            'details.metode' => 'required|string',
            'details.deskripsi_lengkap' => 'required|string',
            'details.jenis_sampel' => 'required|array|min:1',
            'details.jenis_sampel.*.tipe' => 'required|string',
            'details.jenis_sampel.*.spek' => 'required|string',
        ]);

        $data = $request->only(['code', 'name', 'description']);
        $data['details'] = json_encode($request->details);

        Module::create($data);

        return redirect()->route('module.dashboard')->with('success', 'Data Module berhasil ditambahkan!');
    }

    public function edit($id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized. Anda tidak memiliki akses untuk mengedit data ini.');
        }

        $Module = Module::findOrFail($id);
        return view('module.edit', compact('Module'));
    }

    public function update(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized. Anda tidak memiliki akses untuk memperbarui data ini.');
        }

        $Module = Module::findOrFail($id);

        $request->validate([
            'code' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'details.alat' => 'required|string',
            'details.metode' => 'required|string',
            'details.deskripsi_lengkap' => 'required|string',
            'details.jenis_sampel' => 'required|array|min:1',
            'details.jenis_sampel.*.tipe' => 'required|string',
            'details.jenis_sampel.*.spek' => 'required|string',
        ]);

        $Module->update($request->all());
        return redirect()->route('module.dashboard')->with('success', 'Data Module berhasil diperbarui!');
    }

    public function destroy($id)
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Unauthorized. Anda tidak memiliki akses untuk menghapus data ini.'], 403);
        }

        $Module = Module::findOrFail($id);
        $Module->delete();

        return response()->json(['success' => true, 'message' => 'Data Module berhasil dihapus.']);
    }
    public function show(Module $module): View
    {
        // Method ini hanya bertugas menampilkan view utama,
        // dan mengirimkan data modul yang sedang dibuka.
        // Data untuk tabel akan diambil via AJAX oleh method data().
        return view('module.show', [
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
