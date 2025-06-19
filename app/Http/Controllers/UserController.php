<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function getData(Request $request)
    {
        $data = User::where ('role','mitra')->select(['id', 'name', 'email', 'phone', 'institution', 'role','coordinator_name', 'is_active']);

        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('is_active', function ($row) {
                return $row->is_active == 1 ? 'Aktif' : 'Tidak Aktif';
            })
            ->addColumn('aksi', function ($row) {
                return '<a href="/user/' . $row->id . '/edit" class="btn btn-sm btn-primary">Edit</a>';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }
}
