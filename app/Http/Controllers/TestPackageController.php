<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\TestPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class TestPackageController extends Controller
{
    public function index()
    {

        return view('test_package.dashboard');
    }

    public function getData(Request $request)
    {
        $data = TestPackage::with('module');

        // Mendapatkan user yang sedang login untuk pengecekan role.
        $user = Auth::user();

        // Menggunakan Yajra DataTables untuk memproses data.
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('module', function ($row) {
                return $row->module ? $row->module->name : '-';
            })
            ->addColumn('aksi', function ($row) use ($user) { // Melewatkan $user ke dalam closure.
                $buttons = '';
                // Tombol aksi (edit dan hapus) hanya ditampilkan jika user adalah 'admin'.
                if ($user && $user->role === 'admin') {
                    // Membuat URL untuk tombol Edit menggunakan helper route().
                    $editUrl = route('test_package.edit', $row->id);

                    $buttons .= '
                        <a href="' . $editUrl . '" class="btn btn-sm btn-icon btn-primary me-1" title="Edit">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-pencil" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M15 6l3 3l-9 9h-3v-3z" />
                                <path d="M18 3l3 3" />
                            </svg>
                        </a>
                        <button type="button" class="btn btn-sm btn-icon btn-danger btn-delete-test-package"
                                data-bs-toggle="modal" data-bs-target="#deleteConfirmationModal"
                                data-id="' . $row->id . '"
                                data-test-package-name="' . htmlspecialchars($row->nama_test_package) . '" title="Hapus">
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
                return $buttons; // Mengembalikan HTML tombol aksi.
            })
            ->rawColumns(['aksi']) // Memberi tahu DataTables untuk merender HTML di kolom 'aksi'.
            ->make(true); // Membuat respons JSON yang siap digunakan oleh DataTables.
    }

    public function create()
    {
        $modules = Module::all();
        return view('test_package.create', compact('modules'));
    }

    public function store(Request $request)
    {
        // Memvalidasi data yang masuk dari form.
        $request->validate([
            'module_id' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
        ]);

        TestPackage::create($request->all());

        return redirect()->route('test_package.dashboard')->with('success', 'Data Paket Pengujian berhasil ditambahkan!');
    }

    public function edit($id)
    {
        // Memastikan hanya 'admin' yang bisa mengakses fungsi edit.
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized. Anda tidak memiliki akses untuk mengedit data ini.');
        }

        // Mencari data TestPackage berdasarkan ID atau menampilkan error 404 jika tidak ditemukan.
        $testPackage = TestPackage::findOrFail($id);
        $modules = \App\Models\Module::all();

        return view('test_package.edit', compact('testPackage', 'modules'));
    }

    public function update(Request $request, $id)
    {
        // Memastikan hanya 'admin' yang bisa mengakses fungsi update.
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized. Anda tidak memiliki akses untuk memperbarui data ini.');
        }

        // Mencari data TestPackage yang akan diupdate.
        $testPackage = TestPackage::findOrFail($id);

        // Memvalidasi data yang masuk dari form update.
        $request->validate([
            'module_id' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
        ]);

        // Memperbarui record di database dengan data baru dari request.
        $testPackage->update($request->all());

        // Mengalihkan user kembali ke halaman dashboard dengan pesan sukses.
        return redirect()->route('test_package.dashboard')->with('success', 'Data Paket Pengujian berhasil diperbarui!');
    }

    public function destroy($id)
    {
        // Memastikan hanya 'admin' yang bisa mengakses fungsi hapus.
        if (Auth::user()->role !== 'admin') {
            // Mengembalikan respons JSON dengan status 403 (Forbidden) jika tidak berwenang.
            return response()->json(['success' => false, 'message' => 'Unauthorized. Anda tidak memiliki akses untuk menghapus data ini.'], 403);
        }

        // Mencari data TestPackage yang akan dihapus.
        $testPackage = TestPackage::findOrFail($id);
        // Menghapus record dari database.
        $testPackage->delete();

        // Mengembalikan respons JSON dengan pesan sukses.
        return response()->json(['success' => true, 'message' => 'Data Paket Pengujian berhasil dihapus.']);
    }
}
