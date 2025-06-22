<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
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

Route::middleware(['auth'])->group(function () {
    Route::get('/user', function () {
        return view('user.teknisi');
    })->name('user.teknisi');

    Route::get('/user/data', [UserController::class, 'getData'])->name('user.data');

    Route::middleware('can:isAdmin')->group(function () {
        Route::get('/user/{id}/edit', [UserController::class, 'edit']);
        Route::delete('/user/{id}', [UserController::class, 'destroy']);
    });
});


require __DIR__ . '/auth.php';
