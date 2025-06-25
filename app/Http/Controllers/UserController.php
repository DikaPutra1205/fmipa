<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function getData(Request $request)
    {
        $user = Auth::user();
        $data = User::query();

        $data->whereIn('role', ['teknisi_lab', 'tenaga_ahli']);


        // Ambil kolom yang dibutuhkan
        $data->select(['id', 'name', 'email', 'phone', 'institution', 'role', 'coordinator_name', 'is_active']);

        // Jika admin, tambahkan kolom aksi
        if ($user && $user->role === 'admin') {
            return DataTables::of($data)
                ->addIndexColumn() // Untuk kolom 'No'
                ->editColumn('is_active', function ($row) {
                    // Mengubah nilai boolean is_active menjadi 'Aktif' atau 'Tidak Aktif'
                    return $row->is_active == 1 ? 'Aktif' : 'Tidak Aktif';
                })
                ->addColumn('aksi', function ($row) {
                    // Menggunakan fungsi route() untuk URL edit
                    $editUrl = route('teknisi.edit', $row->id);
                    // Menggunakan fungsi route() untuk URL delete
                    // Perhatikan bahwa tombol delete akan dihandle oleh JavaScript
                    $deleteUrl = route('teknisi.destroy', $row->id);

                    return '
                        <a href="' . $editUrl . '" class="btn btn-sm btn-icon btn-primary me-1" title="Edit">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-pencil" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M15 6l3 3l-9 9h-3v-3z" />
                                <path d="M18 3l3 3" />
                            </svg>
                        </a>
                        <button type="button" class="btn btn-sm btn-icon btn-danger btn-delete-teknisi"
                                data-bs-toggle="modal" data-bs-target="#deleteConfirmationModal"
                                data-id="' . $row->id . '"
                                data-user-name="' . htmlspecialchars($row->name) . '" title="Hapus">
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
                ->rawColumns(['aksi']) // Penting agar HTML dirender
                ->make(true);
        } else {
            // Untuk user non-admin
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('is_active', function ($row) {
                    return $row->is_active == 1 ? 'Aktif' : 'Tidak Aktif';
                })
                ->make(true);
        }
    }

    public function create()
    {
        // Opsional: Anda bisa menambahkan pengecekan role di sini
        // Misalnya, hanya user dengan role 'admin' yang bisa menambah data user

        // Mengembalikan view yang berisi form penambahan data TA & Teknisi
        // Pastikan path view ini sesuai dengan lokasi file Blade Anda
        // Contoh: resources/views/users/create.blade.php
        return view('teknisi.create');
    }

    /**
     * Simpan Tenaga Ahli / Teknisi yang baru dibuat ke database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validasi data yang masuk dari form
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email', // Email harus unik di tabel 'users'
            'password' => 'required|string|min:8', // Password minimal 8 karakter
            'role' => 'required|in:teknisi_lab,tenaga_ahli', // Hanya role yang diizinkan
            'coordinator_name' => 'nullable|string|max:255', // 'nullable' jika kolom ini boleh kosong
            'phone' => 'nullable|string|max:20', // 'nullable' jika boleh kosong, sesuaikan panjang max
            'status' => 'required|boolean', // Harus boolean (1 atau 0)
        ]);

        // Buat user baru di database
        // Gunakan Hash::make() untuk meng-hash password sebelum disimpan
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // <<< PASSWORD DI-HASH DI SINI
            'role' => $request->role,
            'coordinator_name' => $request->coordinator_name,
            'phone' => $request->phone,
            'is_active' => $request->status,
            // 'email_verified_at' => now(), // Opsional: jika Anda ingin langsung menandai email terverifikasi
        ]);

        // Redirect ke halaman dashboard Data TA & Teknisi dengan pesan sukses
        // Ganti 'nama_rute_dashboard_ta_teknisi' dengan nama rute aktual dashboard Anda
        // Contoh: jika rute dashboard Anda adalah 'teknisi.dashboard', maka ganti di sini
        return redirect()->route('teknisi.dashboard')->with('success', 'Data Tenaga Ahli & Teknisi berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        // Opsional: Pastikan hanya admin yang bisa menghapus
        if (Auth::user()->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Unauthorized. Anda tidak memiliki akses untuk menghapus user.'], 403);
        }

        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['success' => true, 'message' => 'Data Tenaga Ahli & Teknisi berhasil dihapus.']);
    }

    public function edit($id)
    {
        // Opsional: Pastikan hanya admin yang bisa mengakses ini
        // if (Auth::user()->role !== 'admin') {
        //     abort(403, 'Unauthorized.');
        // }

        $user = User::findOrFail($id); // Mencari user berdasarkan ID atau menampilkan 404
        return view('teknisi.edit', compact('user')); // Melewatkan objek $user ke view
    }

    /**
     * Update Tenaga Ahli / Teknisi yang spesifik di database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // Opsional: Pastikan hanya admin yang bisa mengakses ini
        // if (Auth::user()->role !== 'admin') {
        //     abort(403, 'Unauthorized.');
        // }

        $user = User::findOrFail($id); // Temukan user yang akan diupdate

        // Validasi data yang masuk
        $request->validate([
            'name' => 'required|string|max:255',
            // Email harus unik, tapi abaikan email user saat ini
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8', // Password opsional saat edit
            'role' => 'required|in:teknisi_lab,tenaga_ahli,admin',
            'coordinator_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20', // Perhatikan nama input 'phone'
            'is_active' => 'required|boolean', // Perhatikan nama input 'is_active'
        ]);

        // Update data user
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->coordinator_name = $request->coordinator_name;
        $user->phone = $request->phone; // Menggunakan 'phone' sesuai input name
        $user->is_active = $request->is_active; // Menggunakan 'is_active' sesuai input name

        // Update password hanya jika diisi
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save(); // Simpan perubahan

        // Redirect ke halaman dashboard dengan pesan sukses
        return redirect()->route('teknisi.dashboard')->with('success', 'Data Tenaga Ahli & Teknisi berhasil diperbarui!');
    }
}
