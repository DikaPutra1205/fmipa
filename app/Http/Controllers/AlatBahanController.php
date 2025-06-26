<?php

namespace App\Http\Controllers;

use App\Models\AlatBahan; // Impor model AlatBahan
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Untuk mendapatkan user yang sedang login
use Yajra\DataTables\Facades\DataTables; // Untuk mengelola DataTables

class AlatBahanController extends Controller
{
    /**
     * Menampilkan view halaman dashboard alat dan bahan.
     * Rute: GET /alat-bahan
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Mengembalikan view untuk dashboard Alat & Bahan.
        // Pastikan Anda memiliki file Blade di resources/views/alat_bahan/dashboard.blade.php
        return view('alat_bahan.dashboard');
    }

    /**
     * Mengambil data alat dan bahan untuk DataTables.
     * Data ini akan di-*fetch* oleh JavaScript DataTables melalui AJAX.
     * Rute: GET /alat-bahan/data
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getData(Request $request)
    {
        // Mengambil semua data dari model AlatBahan.
        $data = AlatBahan::query();

        // Mendapatkan user yang sedang login untuk pengecekan role.
        $user = Auth::user();

        // Menggunakan Yajra DataTables untuk memproses data.
        return DataTables::of($data)
            ->addIndexColumn() // Menambahkan kolom 'No' (nomor urut) otomatis.
            ->editColumn('status_data', function ($row) {
                // Mengubah nilai boolean 'status_data' menjadi string 'Aktif' atau 'Tidak Aktif'.
                return $row->status_data ? 'Aktif' : 'Tidak Aktif';
            })
            ->addColumn('aksi', function ($row) use ($user) { // Melewatkan $user ke dalam closure.
                $buttons = '';
                // Tombol aksi (edit dan hapus) hanya ditampilkan jika user adalah 'admin'.
                if ($user && $user->role === 'admin') {
                    // Membuat URL untuk tombol Edit menggunakan helper route().
                    $editUrl = route('alat_bahan.edit', $row->id);

                    $buttons .= '
                        <a href="' . $editUrl . '" class="btn btn-sm btn-icon btn-primary me-1" title="Edit">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-pencil" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M15 6l3 3l-9 9h-3v-3z" />
                                <path d="M18 3l3 3" />
                            </svg>
                        </a>
                        <button type="button" class="btn btn-sm btn-icon btn-danger btn-delete-alat-bahan"
                                data-bs-toggle="modal" data-bs-target="#deleteConfirmationModal"
                                data-id="' . $row->id . '"
                                data-alat-bahan-name="' . htmlspecialchars($row->nama_alat_bahan) . '" title="Hapus">
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

    /**
     * Menampilkan form untuk membuat Alat & Bahan baru.
     * Rute: GET /alat-bahan/create
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Opsional: Anda bisa menambahkan pengecekan role di sini
        // Misalnya, hanya user dengan role 'admin' yang bisa menambah data alat & bahan.
        // if (Auth::user()->role !== 'admin') {
        //     abort(403, 'Unauthorized. Anda tidak memiliki akses untuk menambah data alat & bahan.');
        // }

        // Mengembalikan view yang berisi form penambahan data Alat & Bahan.
        // Pastikan Anda memiliki file Blade di resources/views/alat_bahan/create.blade.php
        return view('alat_bahan.create');
    }

    /**
     * Menyimpan Alat & Bahan yang baru dibuat ke database.
     * Rute: POST /alat-bahan
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Memvalidasi data yang masuk dari form.
        $request->validate([
            'nama_alat_bahan' => 'required|string|max:255',
            'kondisi_alat' => 'required|string|max:255', // Asumsi string, sesuaikan jika enum/lainnya
            'jumlah_alat' => 'required|integer|min:0',
            'status_data' => 'required|boolean',
        ]);

        // Membuat record baru di tabel 'alat_bahans' menggunakan Eloquent ORM.
        // Pastikan kolom-kolom ini ada di properti $fillable di model AlatBahan.
        AlatBahan::create($request->all());

        // Mengalihkan user kembali ke halaman dashboard Alat & Bahan dengan pesan sukses.
        // Ganti 'alat_bahan.dashboard' dengan nama rute aktual dashboard Anda jika berbeda.
        return redirect()->route('alat_bahan.dashboard')->with('success', 'Data Alat & Bahan berhasil ditambahkan!');
    }

    /**
     * Menampilkan form untuk mengedit Alat & Bahan tertentu.
     * Rute: GET /alat-bahan/{id}/edit
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        // Memastikan hanya 'admin' yang bisa mengakses fungsi edit.
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized. Anda tidak memiliki akses untuk mengedit data ini.');
        }

        // Mencari data AlatBahan berdasarkan ID atau menampilkan error 404 jika tidak ditemukan.
        $alatBahan = AlatBahan::findOrFail($id);

        // Mengembalikan view untuk form edit, melewatkan objek $alatBahan ke view.
        // Pastikan Anda memiliki file Blade di resources/views/alat_bahan/edit.blade.php
        return view('alat_bahan.edit', compact('alatBahan'));
    }

    /**
     * Menyimpan perubahan (update) pada Alat & Bahan yang spesifik di database.
     * Rute: PUT /alat-bahan/{id}
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // Memastikan hanya 'admin' yang bisa mengakses fungsi update.
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized. Anda tidak memiliki akses untuk memperbarui data ini.');
        }

        // Mencari data AlatBahan yang akan diupdate.
        $alatBahan = AlatBahan::findOrFail($id);

        // Memvalidasi data yang masuk dari form update.
        $request->validate([
            'nama_alat_bahan' => 'required|string|max:255',
            'kondisi_alat' => 'required|string|max:255',
            'jumlah_alat' => 'required|integer|min:0',
            'status_data' => 'required|boolean',
        ]);

        // Memperbarui record di database dengan data baru dari request.
        $alatBahan->update($request->all());

        // Mengalihkan user kembali ke halaman dashboard dengan pesan sukses.
        return redirect()->route('alat_bahan.dashboard')->with('success', 'Data Alat & Bahan berhasil diperbarui!');
    }

    /**
     * Menghapus data Alat & Bahan tertentu dari database.
     * Rute: DELETE /alat-bahan/{id}
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        // Memastikan hanya 'admin' yang bisa mengakses fungsi hapus.
        if (Auth::user()->role !== 'admin') {
            // Mengembalikan respons JSON dengan status 403 (Forbidden) jika tidak berwenang.
            return response()->json(['success' => false, 'message' => 'Unauthorized. Anda tidak memiliki akses untuk menghapus data ini.'], 403);
        }

        // Mencari data AlatBahan yang akan dihapus.
        $alatBahan = AlatBahan::findOrFail($id);
        // Menghapus record dari database.
        $alatBahan->delete();

        // Mengembalikan respons JSON dengan pesan sukses.
        return response()->json(['success' => true, 'message' => 'Data Alat & Bahan berhasil dihapus.']);
    }
}
