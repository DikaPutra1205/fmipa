<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $data->select(['id','institution', 'coordinator_name', 'phone','is_active']);

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

    public function destroy($id)
    {
        // Hanya admin boleh hapus
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['success' => true]);
    }

    public function edit($id)
    {
        // Hanya admin boleh akses form edit
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $user = User::findOrFail($id);
        return view('mitra.edit', compact('user'));
    }
}
