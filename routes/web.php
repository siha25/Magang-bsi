<?php

use App\Http\Controllers\AnakAsuhController;
use App\Http\Controllers\AsramaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// Rute Autentikasi Pengurus
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Rute Operasional Sistem (Wajib Login)
Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Modul Data Asrama
    Route::prefix('asrama')->name('asrama.')->group(function () {
        Route::get('/', [AsramaController::class, 'index'])->name('index');
        Route::get('/tambah', [AsramaController::class, 'create'])->name('create');
        Route::post('/', [AsramaController::class, 'store'])->name('store');
        Route::get('/{asrama}/edit', [AsramaController::class, 'edit'])->name('edit');
        Route::put('/{asrama}', [AsramaController::class, 'update'])->name('update');
        Route::patch('/{asrama}/toggle-status', [AsramaController::class, 'toggleStatus'])->name('toggle-status');
    });

    // Modul Data Anak Asuh
    Route::prefix('anak-asuh')->name('anak-asuh.')->group(function () {
        Route::get('/', [AnakAsuhController::class, 'index'])->name('index');
        Route::get('/tambah', [AnakAsuhController::class, 'create'])->name('create');
        Route::post('/', [AnakAsuhController::class, 'store'])->name('store');
        Route::get('/{anakAsuh}/edit', [AnakAsuhController::class, 'edit'])->name('edit');
        Route::put('/{anakAsuh}', [AnakAsuhController::class, 'update'])->name('update');
        Route::patch('/{anakAsuh}/toggle-status', [AnakAsuhController::class, 'toggleStatus'])->name('toggle-status');
    });
});
