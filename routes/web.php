<?php

// BAGIAN: SHARED - Definisi Rute Operasional Sistem Multi-Cabang & Pusat

use App\Cabang\Controllers\AnakAsuhController as CabangAnakAsuhController;
use App\Cabang\Controllers\DashboardController as CabangDashboardController;
use App\Cabang\Controllers\DiniyahController as CabangDiniyahController;
use App\Cabang\Controllers\HafalanQuranController as CabangHafalanQuranController;
use App\Cabang\Controllers\KegiatanHarianController as CabangKegiatanHarianController;
use App\Http\Controllers\AnakAsuhController;
use App\Http\Controllers\AsramaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CabangController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EvaluasiDiniyahController;
use App\Http\Controllers\KegiatanHarianController;
use App\Http\Controllers\UserController;
use App\Pusat\Controllers\DashboardController as PusatDashboardController;
use App\Pusat\Controllers\EvaluasiDiniyahController as PusatEvaluasiDiniyahController;
use App\Pusat\Controllers\KelolaCabangController as PusatKelolaCabangController;
use App\Pusat\Controllers\MonitoringDiniyahController as PusatMonitoringDiniyahController;
use App\Pusat\Controllers\MonitoringKegiatanController as PusatMonitoringKegiatanController;
use Illuminate\Support\Facades\Route;

// Rute Autentikasi Pengurus
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Rute Legacy Manajemen Data Cabang (Kompatibilitas Pengujian)
Route::prefix('data-cabang')->group(function () {
    Route::get('/', [CabangController::class, 'index'])->name('cabang.index');
    Route::get('/tambah', [CabangController::class, 'create'])->name('cabang.create');
    Route::post('/', [CabangController::class, 'store'])->name('cabang.store');
    Route::get('/{cabang}/edit', [CabangController::class, 'edit'])->name('cabang.edit');
    Route::put('/{cabang}', [CabangController::class, 'update'])->name('cabang.update');
    Route::patch('/{cabang}/toggle-status', [CabangController::class, 'toggleStatus'])->name('cabang.toggle-status');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Rute Operasional Sistem (Wajib Login & Akun Aktif)
Route::middleware(['auth', 'active'])->group(function () {

    // Rute Root & Dashboard Umum
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('role:admin_pusat,admin_cabang,pimpinan_pusat');

    // =========================================================================
    // MODUL PENGURUS CABANG (Operasional Input Data Cabang Sendiri)
    // =========================================================================
    Route::prefix('cabang')->name('cabang.')->middleware('role:admin_cabang')->group(function () {
        Route::get('/', [CabangDashboardController::class, 'index'])->name('dashboard');

        // Input & Rekap Kegiatan Harian Cabang
        Route::prefix('kegiatan-harian')->name('kegiatan-harian.')->group(function () {
            Route::get('/', [CabangKegiatanHarianController::class, 'index'])->name('index');
            Route::get('/create', [CabangKegiatanHarianController::class, 'create'])->name('create');
            Route::post('/', [CabangKegiatanHarianController::class, 'store'])->name('store');
            Route::get('/{id}', [CabangKegiatanHarianController::class, 'show'])->name('show');
        });

        // Input & Evaluasi Hafalan Al-Qur'an Cabang
        Route::prefix('hafalan-quran')->name('hafalan-quran.')->group(function () {
            Route::get('/', [CabangHafalanQuranController::class, 'index'])->name('index');
            Route::get('/create', [CabangHafalanQuranController::class, 'create'])->name('create');
            Route::post('/', [CabangHafalanQuranController::class, 'store'])->name('store');
            Route::get('/{id}', [CabangHafalanQuranController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [CabangHafalanQuranController::class, 'edit'])->name('edit');
            Route::put('/{id}', [CabangHafalanQuranController::class, 'update'])->name('update');
        });

        // Input & Evaluasi Pengetahuan Diniyah Cabang
        Route::prefix('diniyah')->name('diniyah.')->group(function () {
            Route::get('/', [CabangDiniyahController::class, 'index'])->name('index');
            Route::get('/create', [CabangDiniyahController::class, 'create'])->name('create');
            Route::post('/', [CabangDiniyahController::class, 'store'])->name('store');
            Route::get('/{id}', [CabangDiniyahController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [CabangDiniyahController::class, 'edit'])->name('edit');
            Route::put('/{id}', [CabangDiniyahController::class, 'update'])->name('update');
        });

        // Daftar Santri Cabang
        Route::get('/santri', [CabangAnakAsuhController::class, 'index'])->name('santri.index');
    });

    // =========================================================================
    // MODUL PENGURUS PUSAT (Monitoring, Analitik, & Manajemen Multi-Cabang)
    // =========================================================================
    Route::prefix('pusat')->name('pusat.')->middleware('role:pusat')->group(function () {
        Route::get('/', [PusatDashboardController::class, 'index'])->name('dashboard');

        // Monitoring Kegiatan Harian Nasional (Read-Only)
        Route::get('/monitoring-kegiatan', [PusatMonitoringKegiatanController::class, 'index'])->name('monitoring-kegiatan.index');

        // Monitoring Capaian Diniyah Nasional (Read-Only)
        Route::get('/monitoring-diniyah', [PusatMonitoringDiniyahController::class, 'index'])->name('monitoring-diniyah.index');

        // Evaluasi Ringan Diniyah untuk Santri di Bawah Batas KKM
        Route::get('/evaluasi-diniyah', [PusatEvaluasiDiniyahController::class, 'index'])->name('evaluasi-diniyah.index');
        Route::post('/evaluasi-diniyah/{id}/catatan', [PusatEvaluasiDiniyahController::class, 'simpanCatatan'])->name('evaluasi-diniyah.simpan-catatan');

        // Manajemen Akun & Wilayah Cabang (Khusus Admin Pusat)
        Route::prefix('kelola-cabang')->name('kelola-cabang.')->middleware('role:admin_pusat')->group(function () {
            Route::get('/', [PusatKelolaCabangController::class, 'index'])->name('index');
            Route::get('/create', [PusatKelolaCabangController::class, 'create'])->name('create');
            Route::post('/', [PusatKelolaCabangController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [PusatKelolaCabangController::class, 'edit'])->name('edit');
            Route::put('/{id}', [PusatKelolaCabangController::class, 'update'])->name('update');
            Route::post('/{id}/reset-password', [PusatKelolaCabangController::class, 'resetPassword'])->name('reset-password');
            Route::patch('/{id}/toggle-status', [PusatKelolaCabangController::class, 'toggleStatus'])->name('toggle-status');
        });
    });

    // =========================================================================
    // MODUL EXISTING / SHARED (Kompatibilitas Penuh & Backward Compatibility)
    // =========================================================================

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

    // Modul Evaluasi Diniyah
    Route::prefix('evaluasi-diniyah')->name('evaluasi-diniyah.')->group(function () {
        Route::get('/', [EvaluasiDiniyahController::class, 'index'])->name('index')->middleware('role:admin_pusat,admin_cabang,pimpinan_pusat');
        Route::get('/tambah', [EvaluasiDiniyahController::class, 'create'])->name('create')->middleware('role:admin_pusat,admin_cabang');
        Route::post('/', [EvaluasiDiniyahController::class, 'store'])->name('store')->middleware('role:admin_pusat,admin_cabang');
        Route::get('/asrama/{asramaId}/santri', [EvaluasiDiniyahController::class, 'getSantriByAsrama'])->name('santri-by-asrama')->middleware('role:admin_pusat,admin_cabang');
        Route::get('/{evaluasiDiniyah}', [EvaluasiDiniyahController::class, 'show'])->name('show')->middleware('role:admin_pusat,admin_cabang,pimpinan_pusat');
        Route::get('/{evaluasiDiniyah}/edit', [EvaluasiDiniyahController::class, 'edit'])->name('edit')->middleware('role:admin_pusat,admin_cabang');
        Route::put('/{evaluasiDiniyah}', [EvaluasiDiniyahController::class, 'update'])->name('update')->middleware('role:admin_pusat,admin_cabang');
        Route::delete('/{evaluasiDiniyah}', [EvaluasiDiniyahController::class, 'destroy'])->name('destroy')->middleware('role:admin_pusat,admin_cabang');
    });

    // Modul Pengelola Akun Pengguna (Khusus Admin Pusat)
    Route::prefix('pengguna')->name('users.')->middleware('role:admin_pusat')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/tambah', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::post('/{user}/reset-password', [UserController::class, 'resetPassword'])->name('reset-password');
        Route::patch('/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('toggle-status');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
    });
});
