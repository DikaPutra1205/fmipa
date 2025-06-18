<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Institusi;
use Yajra\DataTables\Facades\DataTables;

class InstitusiController extends Controller
{
    public function getData(Request $request)
    {
        $data = Institusi::select(['id', 'nama_institusi', 'nama_koordinator', 'telp_wa', 'status']);

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
