<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class InstitusiController extends Controller
{
    public function getData(Request $request)
    {
        $data = User::select(['id', 'institution', 'coordinator_name', 'phone', 'is_active']);

        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('is_active', function ($row) {
                return $row->status == 1 ? 'Aktif' : 'Tidak Aktif';
            })
            ->addColumn('aksi', function ($row) {
                return '<a href="/institusi/' . $row->id . '/edit" class="btn btn-sm btn-primary">Edit</a>';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }
}
