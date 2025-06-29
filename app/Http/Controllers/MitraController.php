<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class MitraController extends Controller
{
    public function getData(Request $request)
    {
        $user = Auth::user();
        $data = User::query();

        if ($user->role !== 'mitra') {
            // Selain admin, tidak boleh melihat user dengan role admin atau mitra
            $data->whereIn('role', ['mitra']);
        } elseif ($user->role === 'mitra') {
            $data->where('id', $user->id);
        } else {
            $data->where('id', null);
        }

        // Ambil kolom yang dibutuhkan
        $data->select(['id', 'institution', 'name', 'email', 'phone', 'is_active']);

        // Jika admin, tambahkan kolom aksi
        if ($user->role === 'admin') {
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('is_active', function ($row) {
                    return $row->is_active == 1 ? 'Aktif' : 'Tidak Aktif';
                })
                ->addColumn('aksi', function ($row) {
                    $editUrl = url('/mitra/' . $row->id . '/edit');
                    return '
                    <a href="' . $editUrl . '" class="btn btn-sm btn-icon btn-primary me-1" title="Edit">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-pencil" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M15 6l3 3l-9 9h-3v-3z" />
                            <path d="M18 3l3 3" />
                        </svg>
                    </a>
                    <button class="btn btn-sm btn-icon btn-danger btn-delete-user" data-id="' . $row->id . '" title="Hapus">
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

                ->rawColumns(['aksi'])
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
        return view('mitra.create');
    }
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'institution' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'phone' => 'nullable|string',
            'is_active' => 'required|in:0,1',
        ], [
            'email.unique' => 'Email ini sudah digunakan. Silakan gunakan email lain.',
        ]);

        // Simpan ke DB
        User::create([
            'institution' => $validated['institution'],
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'phone' => $validated['phone'],
            'is_active' => $validated['is_active'],
            'role' => 'mitra', // <== jangan lupa role mitra jika perlu
        ]);

        return redirect()->route('mitra.dashboard')->with('success', 'Mitra berhasil ditambahkan');
    }



    public function destroy($id)
    {
        // Hanya admin boleh hapus
        if (Auth::user()->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Unauthorized. Anda tidak memiliki akses untuk menghapus user.'], 403);
        }

        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['success' => true, 'message' => 'Data mitra berhasil dihapus.']);
    }

    public function edit($id)
    {
        // Hanya admin boleh hapus
        if (Auth::user()->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Unauthorized. Anda tidak memiliki akses untuk menghapus user.'], 403);
        }

        $user = User::findOrFail($id);
        return view('mitra.edit', compact('user'));
    }
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id); // Temukan user yang akan diupdate

        // Validasi data yang masuk
        $request->validate([
            'institution' => 'required|string|max:255',
            'name' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8', // Password opsional saat edit
            'phone' => 'nullable|string|max:20',
            'is_active' => 'required|boolean',
        ]);

        // Update data user
        $user->institution = $request->institution;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->is_active = $request->is_active;
        $user->role = 'mitra';

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        // Redirect ke halaman dashboard dengan pesan sukses
        return redirect()->route('mitra.dashboard')->with('success', 'Data mitra berhasil diperbarui!');
    }
}
