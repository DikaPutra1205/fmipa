<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Auth;


Route::get('/', function () {
    if (Auth::check()) {
        $role = Auth::user()->role;

        return match ($role) {
            'admin' => redirect('/admin/dashboard'),
            'mitra' => redirect('/mitra/dashboard'),
            'teknisi_lab' => redirect('/teknisi_lab/dashboard'),
            'tenaga_ahli' => redirect('/tenaga_ahli/dashboard'),
            default => redirect('/dashboard'),
        };
    }

    return redirect('/login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', RoleMiddleware::class . ':admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return 'Admin Page';
    });
});

Route::middleware(['auth', RoleMiddleware::class . ':mitra'])->group(function () {
    Route::get('/mitra/dashboard', function () {
        return 'Mitra Page';
    });
});

Route::middleware(['auth', RoleMiddleware::class . ':teknisi_lab'])->group(function () {
    Route::get('/teknisi_lab/dashboard', function () {
        return 'Teknisi Lab Page';
    });
});

Route::middleware(['auth', RoleMiddleware::class . ':tenaga_ahli'])->group(function () {
    Route::get('/tenaga_ahli/dashboard', function () {
        return 'Tenaga Ahli Page';
    });
});


require __DIR__.'/auth.php';
