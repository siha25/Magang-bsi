<?php

use App\Http\Controllers\AnakAsuhController;
use App\Http\Controllers\AsramaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KegiatanHarianController;
use Illuminate\Support\Facades\Route;

// Rute Autentikasi Pengurus
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Rute Operasional Sistem (Wajib Login)
Route::middleware(['auth', 'active'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('role:admin_pusat,admin_cabang,pimpinan_pusat');

    // Modul Data Asrama
    Route::prefix('asrama')->name('asrama.')->group(function () {
        Route::get('/', [AsramaController::class, 'index'])->name('index')->middleware('role:admin_pusat,admin_cabang,pimpinan_pusat');
        Route::get('/tambah', [AsramaController::class, 'create'])->name('create')->middleware('role:admin_pusat');
        Route::post('/', [AsramaController::class, 'store'])->name('store')->middleware('role:admin_pusat');
        Route::get('/{asrama}/edit', [AsramaController::class, 'edit'])->name('edit')->middleware('role:admin_pusat');
        Route::put('/{asrama}', [AsramaController::class, 'update'])->name('update')->middleware('role:admin_pusat');
        Route::patch('/{asrama}/toggle-status', [AsramaController::class, 'toggleStatus'])->name('toggle-status')->middleware('role:admin_pusat');
    });

    // Modul Data Anak Asuh
    Route::prefix('anak-asuh')->name('anak-asuh.')->group(function () {
        Route::get('/', [AnakAsuhController::class, 'index'])->name('index')->middleware('role:admin_pusat,admin_cabang,pimpinan_pusat');
        Route::get('/tambah', [AnakAsuhController::class, 'create'])->name('create')->middleware('role:admin_pusat');
        Route::post('/', [AnakAsuhController::class, 'store'])->name('store')->middleware('role:admin_pusat');
        Route::get('/{anakAsuh}/edit', [AnakAsuhController::class, 'edit'])->name('edit')->middleware('role:admin_pusat');
        Route::put('/{anakAsuh}', [AnakAsuhController::class, 'update'])->name('update')->middleware('role:admin_pusat');
        Route::patch('/{anakAsuh}/toggle-status', [AnakAsuhController::class, 'toggleStatus'])->name('toggle-status')->middleware('role:admin_pusat');
    });

    // Modul Kegiatan Harian
    Route::prefix('kegiatan-harian')->name('kegiatan-harian.')->group(function () {
        Route::get('/', [KegiatanHarianController::class, 'index'])->name('index')->middleware('role:admin_pusat,admin_cabang,pimpinan_pusat');
        Route::get('/tambah', [KegiatanHarianController::class, 'create'])->name('create')->middleware('role:admin_pusat,admin_cabang');
        Route::post('/', [KegiatanHarianController::class, 'store'])->name('store')->middleware('role:admin_pusat,admin_cabang');
        Route::get('/{kegiatanHarian}', [KegiatanHarianController::class, 'show'])->name('show')->middleware('role:admin_pusat,admin_cabang,pimpinan_pusat');
    });
});
