<?php

use App\Http\Controllers\AnakAsuhController;
use App\Http\Controllers\AsramaController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard', [DashboardController::class, 'index']);

// Modul Data Asrama
Route::prefix('asrama')->name('asrama.')->group(function () {
    Route::get('/', [AsramaController::class, 'index'])->name('index');

    Route::middleware(['web'])->group(function () {
        Route::get('/tambah', [AsramaController::class, 'create'])->name('create');
        Route::post('/', [AsramaController::class, 'store'])->name('store');
        Route::get('/{asrama}/edit', [AsramaController::class, 'edit'])->name('edit');
        Route::put('/{asrama}', [AsramaController::class, 'update'])->name('update');
        Route::patch('/{asrama}/toggle-status', [AsramaController::class, 'toggleStatus'])->name('toggle-status');
    });
});

// Modul Data Anak Asuh
Route::prefix('anak-asuh')->name('anak-asuh.')->group(function () {
    Route::get('/', [AnakAsuhController::class, 'index'])->name('index');

    Route::middleware(['web'])->group(function () {
        Route::get('/tambah', [AnakAsuhController::class, 'create'])->name('create');
        Route::post('/', [AnakAsuhController::class, 'store'])->name('store');
        Route::get('/{anakAsuh}/edit', [AnakAsuhController::class, 'edit'])->name('edit');
        Route::put('/{anakAsuh}', [AnakAsuhController::class, 'update'])->name('update');
        Route::patch('/{anakAsuh}/toggle-status', [AnakAsuhController::class, 'toggleStatus'])->name('toggle-status');
    });
});
