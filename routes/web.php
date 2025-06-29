<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MitraController;
use App\Http\Controllers\AlatBahanController;
use App\Http\Controllers\SampelMaterialController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\TestPackageController;
use App\Http\Controllers\WizardDispatcherController;
use App\Http\Controllers\SubmissionWizardController;
use App\Http\Controllers\TestingTrackerController;
use App\Http\Controllers\PaymentWizardController;
use App\Http\Controllers\HasilUjiController;

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Teknisi
Route::middleware(['auth'])->group(function () {
    Route::get('/teknisi', fn() => view('teknisi.dashboard'))->name('teknisi.dashboard');
    Route::get('/teknisi/data', [UserController::class, 'getData'])->name('teknisi.data');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/teknisi/{id}/edit', [UserController::class, 'edit'])->name('teknisi.edit');
    Route::put('/teknisi/{id}', [UserController::class, 'update'])->name('teknisi.update');
    Route::delete('/teknisi/{id}', [UserController::class, 'destroy'])->name('teknisi.destroy');
});

// Mitra
Route::middleware(['auth'])->group(function () {
    Route::get('/mitra', fn() => view('mitra.dashboard'))->name('mitra.dashboard');
    Route::get('/mitra/data', [MitraController::class, 'getData'])->name('mitra.data');
    Route::get('/mitra/create', [MitraController::class, 'create'])->name('mitra.create');
    Route::post('/mitra', [MitraController::class, 'store'])->name('mitra.store');
    Route::get('/mitra/{id}/edit', [MitraController::class, 'edit'])->name('mitra.edit');
    Route::put('/mitra/{id}', [MitraController::class, 'update'])->name('mitra.update');
    Route::delete('/mitra/{id}', [MitraController::class, 'destroy'])->name('mitra.destroy');
});

// Alat Bahan
Route::middleware(['auth'])->group(function () {
    Route::get('/alat-bahan', fn() => view('alat_bahan.dashboard'))->name('alat_bahan.dashboard');
    Route::get('/alat-bahan/data', [AlatBahanController::class, 'getData'])->name('alat_bahan.data');
    Route::get('/alat-bahan/create', [AlatBahanController::class, 'create'])->name('alat_bahan.create');
    Route::post('/alat-bahan', [AlatBahanController::class, 'store'])->name('alat_bahan.store');
    Route::get('/alat-bahan/{id}/edit', [AlatBahanController::class, 'edit'])->name('alat_bahan.edit');
    Route::put('/alat-bahan/{id}', [AlatBahanController::class, 'update'])->name('alat_bahan.update');
    Route::delete('/alat-bahan/{id}', [AlatBahanController::class, 'destroy'])->name('alat_bahan.destroy');
});

// Sampel Material
Route::middleware(['auth'])->group(function () {
    Route::get('/sample-material', fn() => view('sample_material.dashboard'))->name('sample_material.dashboard');
    Route::get('/sample-material/data', [SampelMaterialController::class, 'getData'])->name('sample_material.data');
    Route::get('/sample-material/create', [SampelMaterialController::class, 'create'])->name('sample_material.create');
    Route::post('/sample-material', [SampelMaterialController::class, 'store'])->name('sample_material.store');
    Route::get('/sample-material/{id}/edit', [SampelMaterialController::class, 'edit'])->name('sample_material.edit');
    Route::put('/sample-material/{id}', [SampelMaterialController::class, 'update'])->name('sample_material.update');
    Route::delete('/sample-material/{id}', [SampelMaterialController::class, 'destroy'])->name('sample_material.destroy');
});

// Module
Route::middleware(['auth'])->group(function () {
    Route::get('/module', fn() => view('module.dashboard'))->name('module.dashboard');
    Route::get('/module/view', [ModuleController::class, 'getData'])->name('module.view');
    Route::get('/module/data', [ModuleController::class, 'getData'])->name('module.data');
    Route::get('/module/create', [ModuleController::class, 'create'])->name('module.create');
    Route::post('/module', [ModuleController::class, 'store'])->name('module.store');
    Route::get('/module/{id}/edit', [ModuleController::class, 'edit'])->name('module.edit');
    Route::put('/module/{id}', [ModuleController::class, 'update'])->name('module.update');
    Route::delete('/module/{id}', [ModuleController::class, 'destroy'])->name('module.destroy');
});

// Test Package
Route::middleware(['auth'])->group(function () {
    Route::get('/test_package', [TestPackageController::class, 'index'])->name('test_package.dashboard');
    Route::get('/test_package/data', [TestPackageController::class, 'getData'])->name('test_package.data');
    Route::get('/test_package/create', [TestPackageController::class, 'create'])->name('test_package.create');
    Route::post('/test_package', [TestPackageController::class, 'store'])->name('test_package.store');
    Route::get('/test_package/{id}/edit', [TestPackageController::class, 'edit'])->name('test_package.edit');
    Route::put('/test_package/{id}', [TestPackageController::class, 'update'])->name('test_package.update');
    Route::delete('/test_package/{id}', [TestPackageController::class, 'destroy'])->name('test_package.destroy');

    // Modul Dinamis
    Route::get('/modul/{module:code}', [ModuleController::class, 'show'])->name('module.show');
    Route::get('/modul/{module:code}/data', [ModuleController::class, 'data'])->name('module.detail_data');

    // Wizard Dispatcher
    Route::get('/wizard/dispatch/{test}', [WizardDispatcherController::class, 'dispatch'])->name('wizard.dispatcher');

    // Wizard 1 - Pengajuan
    Route::prefix('pengajuan')->name('wizard.submission.')->group(function () {
        Route::get('/create/{module:code}', [SubmissionWizardController::class, 'create'])->name('create');
        Route::post('/store', [SubmissionWizardController::class, 'store'])->name('store');
        Route::get('/{test}', [SubmissionWizardController::class, 'show'])->name('show');
        Route::post('/{test}/action', [SubmissionWizardController::class, 'approveOrReject'])->name('action');
        Route::post('/{test}/store-sample', [SubmissionWizardController::class, 'storeSampleDetails'])->name('storeSample');
        Route::post('/{test}/confirm-receipt', [SubmissionWizardController::class, 'confirmReceipt'])->name('confirmReceipt');
    });

    // Wizard 2 - Pelacakan
    Route::prefix('pelacakan')->name('wizard.tracking.')->group(function () {
        Route::get('/{test}', [TestingTrackerController::class, 'show'])->name('show');
        Route::post('/{test}/upload-result', [TestingTrackerController::class, 'uploadResult'])->name('upload');
        Route::post('/{test}/verify-result', [TestingTrackerController::class, 'verifyResult'])->name('verify');
    });

    // Wizard 3 - Pembayaran
    Route::prefix('pembayaran')->name('wizard.payment.')->group(function () {
        Route::get('/{test}', [PaymentWizardController::class, 'show'])->name('show');
        Route::post('/{test}/confirm', [PaymentWizardController::class, 'confirmPayment'])->name('confirm');
        Route::post('/{test}/complete', [PaymentWizardController::class, 'completeOrder'])->name('complete');
    });

    // Hasil Uji
    Route::prefix('hasil-uji')->name('hasil_uji.')->group(function () {
        Route::get('/', [HasilUjiController::class, 'index'])->name('index');
        Route::get('/data', [HasilUjiController::class, 'data'])->name('data');
    });
});

Route::middleware(['auth'])->group(function () {

    // =====================================================================
    // RUTE UNTUK HALAMAN UTAMA SETIAP MODUL (DINAMIS)
    // =====================================================================
    // Ini menangani URL seperti /modul/xrd, /modul/sem-edx, dll.
    Route::get('/modul/{module:code}', [ModuleController::class, 'show'])->name('module.show');
    Route::get('/modul/{module:code}/data', [ModuleController::class, 'data'])->name('module.data');


    // =====================================================================
    // RUTE "PINTAR" UNTUK MENGARAHKAN KE WIZARD YANG TEPAT
    // =====================================================================
    // Ini adalah "gerbang" yang akan diakses oleh tombol [Lihat Detail]
    Route::get('/wizard/dispatch/{test}', [WizardDispatcherController::class, 'dispatch'])->name('wizard.dispatcher');


    // =====================================================================
    // RUTE UNTUK WIZARD 1: PENGAJUAN & PENGIRIMAN SAMPEL
    // =====================================================================
    Route::prefix('pengajuan')->name('wizard.submission.')->group(function () {
        Route::get('/create/{module:code}', [SubmissionWizardController::class, 'create'])->name('create'); // Untuk tombol [+ Ajukan Pengujian Baru]
        Route::post('/store', [SubmissionWizardController::class, 'store'])->name('store'); // Menyimpan pengajuan awal
        Route::get('/{test}', [SubmissionWizardController::class, 'show'])->name('show');
        Route::post('/{test}/action', [SubmissionWizardController::class, 'approveOrReject'])->name('action');
        Route::post('/{test}/store-sample', [SubmissionWizardController::class, 'storeSampleDetails'])->name('storeSample');
        Route::post('/{test}/confirm-receipt', [SubmissionWizardController::class, 'confirmReceipt'])->name('confirmReceipt');
    });


    // =====================================================================
    // RUTE UNTUK WIZARD 2: PELACAKAN PENGUJIAN & VERIFIKASI
    // =====================================================================
    Route::prefix('pelacakan')->name('wizard.tracking.')->group(function () {
        Route::get('/{test}', [TestingTrackerController::class, 'show'])->name('show');
        Route::post('/{test}/upload-result', [TestingTrackerController::class, 'uploadResult'])->name('upload');
        Route::post('/{test}/verify-result', [TestingTrackerController::class, 'verifyResult'])->name('verify');
    });


    // =====================================================================
    // RUTE UNTUK WIZARD 3: PEMBAYARAN & PENYELESAIAN
    // =====================================================================
    Route::prefix('pembayaran')->name('wizard.payment.')->group(function () {
        Route::get('/{test}', [PaymentWizardController::class, 'show'])->name('show');
        Route::post('/{test}/confirm', [PaymentWizardController::class, 'confirmPayment'])->name('confirm');
        Route::post('/{test}/complete', [PaymentWizardController::class, 'completeOrder'])->name('complete');
    });

});

require __DIR__ . '/auth.php';
