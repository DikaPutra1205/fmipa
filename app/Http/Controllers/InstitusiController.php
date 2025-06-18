<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Institusi;
use App\Models\User;
use Yajra\DataTables\Facades\DataTables;

class InstitusiController extends Controller
{
    public function getData(Request $request)
    {
        $data = User::select(['id', 'institution', 'coordinator_name', 'phone', 'is_active'])->where('role' == 'mitra');
        dd($data);

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('aksi', function ($row) {
                return '<a href="/institusi/'.$row->id.'/edit" class="btn btn-sm btn-primary">Edit</a>';
            })
            ->editColumn('status', function ($row) {
                return $row->status == 1 ? 'Aktif' : 'Tidak Aktif';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }
}
