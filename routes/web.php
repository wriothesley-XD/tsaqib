<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\TsaqibController;
use Illuminate\Support\Facades\Route;

// ==========================================================
// PUBLIK — tanpa login
// ==========================================================
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/perpustakaan', [PageController::class, 'perpustakaan'])->name('perpustakaan');

// TODO: konfirmasi ke tim — apakah route ini masih dipakai atau digabung ke dashboard TSAQIB.
Route::get('/community', [PageController::class, 'community'])->name('community');

// Open Recruitment (daftar member) — publik, sesuai keputusan rapat granular access.
// TODO: cek routes/auth.php — kalau route daftar/POST submit sudah ada di sana, hapus dari sini
// biar nggak dobel. Kalau belum, arahkan ke controller yang benar (PendaftaranController?).
Route::get('/daftar', [PageController::class, 'daftarForm'])->name('daftar.form');

// Labor PAI (visi-misi, struktur formal) — publik (berubah dari wajib login)
Route::get('/labor-pai', [TsaqibController::class, 'laborPai'])->name('labor-pai');

// Informasi Kegiatan FSI — publik (baru)
Route::get('/kegiatan', [TsaqibController::class, 'kegiatan'])->name('kegiatan');

// ==========================================================
// LOGIN — wajib auth
// ==========================================================
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [PageController::class, 'dashboard'])
        ->middleware('verified')
        ->name('dashboard');

    // Informasi Role (detail jabatan internal) — login, beda dari Labor PAI (baru)
    Route::get('/role', [TsaqibController::class, 'role'])->name('role');
});

// ==========================================================
// ADMIN PANEL — kelola buku Pustaka
// TODO: proteksi route ini (middleware auth + role admin), saat ini masih terbuka.
// ==========================================================
Route::prefix('admin-panel')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');            // Daftar Buku
    Route::get('/add', [AdminController::class, 'create'])->name('create');       // Form Tambah
    Route::post('/add', [AdminController::class, 'store'])->name('store');        // Simpan Data Baru
    Route::get('/{id}/edit', [AdminController::class, 'edit'])->name('edit');     // Form Edit
    Route::put('/{id}', [AdminController::class, 'update'])->name('update');      // Simpan Perubahan
    Route::delete('/{id}', [AdminController::class, 'destroy'])->name('destroy'); // Hapus Data
});

require __DIR__.'/auth.php';
