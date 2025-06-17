<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    // Menyediakan data untuk DataTables
    public function getAjaxUsers(Request $request)
    {
        $users = User::select([
            'name as full_name',
            'email',
            'role as position',
            'institution as office',
            'created_at as start_date', // Kalau belum ada data, bisa dummy
            'id as salary' // Ganti sesuai kolom atau dummy
        ])->get();

        return response()->json(['data' => $users]);
    }

    // (Opsional) Buat view jika perlu tampilkan Blade
    public function index()
    {
        return view('users.index'); // misal view lo ada di resources/views/users/index.blade.php
    }
}
