<?php

namespace App\Http\Controllers;

use App\Models\Module;
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



            ->addColumn('aksi', function ($row) use ($user) {
                $buttons = '';
                if ($user && $user->role === 'admin') {
                    $editUrl = route('module.edit', $row->id);

                    $buttons .= '
                        <a href="' . $editUrl . '" class="btn btn-sm btn-icon btn-primary me-1" title="Edit">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-pencil" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M15 6l3 3l-9 9h-3v-3z" />
                                <path d="M18 3l3 3" />
                            </svg>
                        </a>
                        <button type="button" class="btn btn-sm btn-icon btn-danger btn-delete-module"
                                data-bs-toggle="modal" data-bs-target="#deleteConfirmationModal"
                                data-id="' . $row->id . '"
                                data-module-name="' . htmlspecialchars($row->nama_module) . '" title="Hapus">
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
            'description' => 'required|string', // perbaikan dari 'text'
            'details.alat' => 'required|string',
            'details.metode' => 'required|string',
            'details.deskripsi_lengkap' => 'required|string',
            'details.jenis_sampel' => 'required|array|min:1',
            'details.jenis_sampel.*.tipe' => 'required|string',
            'details.jenis_sampel.*.spek' => 'required|string',
        ]);

        $data = $request->only(['code', 'name', 'description']);
        $data['details'] = json_encode($request->details); // konversi array ke JSON string

        Module::create($data);

        return redirect()->route('module.dashboard')->with('success', 'Data Module berhasil ditambahkan!');
    }

    public function edit($id)
    {
        // Memastikan hanya 'admin' yang bisa mengakses fungsi edit.
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized. Anda tidak memiliki akses untuk mengedit data ini.');
        }

        // Mencari data Module berdasarkan ID atau menampilkan error 404 jika tidak ditemukan.
        $Module = Module::findOrFail($id);

        return view('module.edit', compact('Module'));
    }

    public function update(Request $request, $id)
    {
        // Memastikan hanya 'admin' yang bisa mengakses fungsi update.
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized. Anda tidak memiliki akses untuk memperbarui data ini.');
        }

        // Mencari data Module yang akan diupdate.
        $Module = Module::findOrFail($id);

        // Memvalidasi data yang masuk dari form update.
        $request->validate([
            'code' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'description' => 'required|string', // perbaikan dari 'text'
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
        // Memastikan hanya 'admin' yang bisa mengakses fungsi hapus.
        if (Auth::user()->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Unauthorized. Anda tidak memiliki akses untuk menghapus data ini.'], 403);
        }

        $Module = Module::findOrFail($id);
        $Module->delete();
        return response()->json(['success' => true, 'message' => 'Data Module berhasil dihapus.']);
    }
}
