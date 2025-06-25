<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MitraController;
use App\Http\Controllers\AlatBahanController;
use App\Http\Controllers\SampelMaterialController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Auth;


Route::get('/', function () {
    return redirect('/dashboard');
});

// Dashboard tunggal
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//teknisi
Route::middleware(['auth'])->group(function () {
    Route::get('/teknisi', function () {
        return view('teknisi.dashboard');
    })->name('teknisi.dashboard');

    Route::get('/teknisi/data', [UserController::class, 'getData'])->name('teknisi.data');

    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/teknisi/{id}/edit', [UserController::class, 'edit'])->name('teknisi.edit');
    Route::put('/teknisi/{id}', [UserController::class, 'update'])->name('teknisi.update');
    Route::delete('/teknisi/{id}', [UserController::class, 'destroy'])->name('teknisi.destroy');
});


//Mitra
Route::middleware(['auth'])->group(function () {
    Route::get('/mitra', function () {
        return view('mitra.dashboard');
    })->name('mitra.dashboard');

    Route::get('/mitra/data', [MitraController::class, 'getData'])->name('mitra.data');
    
    Route::get('/mitra/create', [MitraController::class, 'create'])->name('mitra.create');
    Route::post('/mitra', [MitraController::class, 'store'])->name('mitra.store');
    Route::get('/mitra/{id}/edit', [MitraController::class, 'edit'])->name('mitra.edit');
    Route::put('/mitra/{id}', [MitraController::class, 'update'])->name('mitra.update');
    Route::delete('/mitra/{id}', [MitraController::class, 'destroy'])->name('mitra.destroy');
});

require __DIR__ . '/auth.php';

//Alat & Bahan
Route::middleware(['auth'])->group(function () {
    Route::get('/alat-bahan', function () {
        return view('alat_bahan.dashboard');
    })->name('alat_bahan.dashboard');

    Route::get('/alat-bahan/data', [AlatBahanController::class, 'getData'])->name('alat_bahan.data');

    // Rute untuk Admin (jika ada edit/delete untuk alat & bahan)
    Route::middleware('can:isAdmin')->group(function () {
        Route::get('/alat-bahan/{id}/edit', [AlatBahanController::class, 'edit'])->name('alat_bahan.edit');
        Route::delete('/alat-bahan/{id}', [AlatBahanController::class, 'destroy'])->name('alat_bahan.destroy');
    });
});

//Sample Material
Route::middleware(['auth'])->group(function () {
    Route::get('/sample-material', function () {
        return view('sample_material.dashboard');
    })->name('sample_material.dashboard');

    Route::get('/sample-material/data', [SampelMaterialController::class, 'getData'])->name('sample_material.data');

    // Rute untuk Admin (jika ada edit/delete untuk Sample Material)
    Route::middleware('can:isAdmin')->group(function () {
        Route::get('/sample-material/{id}/edit', [SampelMaterialController::class, 'edit'])->name('sample_material.edit');
        Route::delete('/sample-material/{id}', [SampelMaterialController::class, 'destroy'])->name('sample_material.destroy');
    });
});
