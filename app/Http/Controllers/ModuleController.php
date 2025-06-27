<?php

namespace App\Http\Controllers;

use App\Models\SampelMaterial; // Import model SampelMaterial
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Jika perlu filter berdasarkan user role
use Yajra\DataTables\Facades\DataTables;

class SampelMaterialController extends Controller
{
    // Method untuk menampilkan view halaman sampel dan material
    public function index()
    {
        return view('sample_material.dashboard');
    }

    /**
     * Mengambil data sampel dan material untuk DataTables.
     * Data ini akan di-*fetch* oleh JavaScript DataTables melalui AJAX.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getData(Request $request)
    {
        $data = SampelMaterial::query();
        $user = Auth::user();

        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('status_data', function ($row) {
                return $row->status_data ? 'Aktif' : 'Tidak Aktif';
            })
            ->addColumn('aksi', function ($row) use ($user) {
                $buttons = '';
                if ($user && $user->role === 'admin') {
                    $editUrl = route('sample_material.edit', $row->id);

                    $buttons .= '
                        <a href="' . $editUrl . '" class="btn btn-sm btn-icon btn-primary me-1" title="Edit">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-pencil" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M15 6l3 3l-9 9h-3v-3z" />
                                <path d="M18 3l3 3" />
                            </svg>
                        </a>
                        <button type="button" class="btn btn-sm btn-icon btn-danger btn-delete-sample-material"
                                data-bs-toggle="modal" data-bs-target="#deleteConfirmationModal"
                                data-id="' . $row->id . '"
                                data-sample-name="' . htmlspecialchars($row->nama_module) . '" title="Hapus">
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
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create()
    {
       
        return view('sample_material.create');
    }


    public function store(Request $request)
    {
        
        $request->validate([
            'code' => 'required|string|min:10',
            'name' => 'required|string|max:255',
            'text' => 'required|text',
        ]);

        SampelMaterial::create($request->all());
        return redirect()->route('sample_material.dashboard')->with('success', 'Data sampel & material berhasil ditambahkan!');
    }


    public function edit($id)
    {
        // Memastikan hanya 'admin' yang bisa mengakses fungsi edit.
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized. Anda tidak memiliki akses untuk mengedit data ini.');
        }

        $sampelMaterial = SampelMaterial::findOrFail($id);

        return view('sample_material.edit', compact('sampelMaterial'));
    }

    public function update(Request $request, $id)
    {
        // Memastikan hanya 'admin' yang bisa mengakses fungsi update.
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized. Anda tidak memiliki akses untuk memperbarui data ini.');
        }

        // Mencari data SampelMaterial yang akan diupdate.
        $sampelMaterial = SampelMaterial::findOrFail($id);

        // Memvalidasi data yang masuk dari form update.
        $request->validate([
            'code' => 'required|string|min:10',
            'name' => 'required|string|max:255',
            'text' => 'required|text',
        ]);

        // Memperbarui record di database dengan data baru dari request.
        $sampelMaterial->update($request->all());

        // Mengalihkan user kembali ke halaman dashboard dengan pesan sukses.
        return redirect()->route('sample_material.dashboard')->with('success', 'Data sampel & material berhasil diperbarui!');
    }


    public function destroy($id)
    {
        // Memastikan hanya 'admin' yang bisa mengakses fungsi hapus.
        if (Auth::user()->role !== 'admin') {
            // Mengembalikan respons JSON dengan status 403 (Forbidden) jika tidak berwenang.
            return response()->json(['success' => false, 'message' => 'Unauthorized. Anda tidak memiliki akses untuk menghapus data ini.'], 403);
        }

        // Mencari data SampelMaterial yang akan dihapus.
        $sampelMaterial = SampelMaterial::findOrFail($id);
        // Menghapus record dari database.
        $sampelMaterial->delete();

        // Mengembalikan respons JSON dengan pesan sukses.
        return response()->json(['success' => true, 'message' => 'Data sampel & material berhasil dihapus.']);
    }
}
