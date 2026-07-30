<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

// ==========================================================
// PUBLIK — tanpa login
// ==========================================================
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/community', [PageController::class, 'community'])->name('community');
Route::get('/dashboard', [PageController::class, 'dashboard'])->name('dashboard');
Route::get('/perpustakaan', [PageController::class, 'perpustakaan'])->name('perpustakaan');

Route::prefix('admin-panel')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');           // Daftar Buku
    Route::get('/add', [AdminController::class, 'create'])->name('create');   // Form Tambah
    Route::post('/add', [AdminController::class, 'store'])->name('store');    // Simpan Data Baru
    Route::get('/{id}/edit', [AdminController::class, 'edit'])->name('edit');    // Form Edit
    Route::put('/{id}', [AdminController::class, 'update'])->name('update');     // Simpan Perubahan
    Route::delete('/{id}', [AdminController::class, 'destroy'])->name('destroy');// Hapus Data
});

Route::get('/', function () {
    return view('home');
});

require __DIR__.'/auth.php';
