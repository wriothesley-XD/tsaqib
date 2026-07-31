<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TsaqibController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OpenRecruitmentController;

// ==========================================================
// PUBLIK — tanpa login
// ==========================================================
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/community', [PageController::class, 'community'])->name('community');
Route::get('/perpustakaan', [PageController::class, 'perpustakaan'])->name('perpustakaan');


Route::get('/', [PageController::class, 'landing'])
    ->name('landing');
Route::get('/komunitas/{slug}', [PageController::class, 'komunitasShow'])
    ->name('komunitas.show');
Route::get('/open-recruitment', [PageController::class, 'openRecruitmentForm'])
    ->name('open.recruitment');
Route::get('/laboratorium-pai', function () {
    return view('laboratorium-pai'); // TODO: buat view ini
})->name('laboratorium.pai');

Route::get('/informasi-kegiatan', function () {
    return view('informasi-kegiatan'); // TODO: buat view ini
})->name('informasi.kegiatan');


// Labor PAI & Informasi Kegiatan FSI — sebelumnya PageController::labor()
// cuma nunjuk ke view placeholder yatim (resources/views/labor.blade.php).
// Implementasi aslinya sudah jadi di TsaqibController tapi tidak pernah
// di-route. Nama route 'labor' dipertahankan karena sudah dipakai di
// beberapa blade lain (navbar, community, perpustakaan).
Route::get('/labor', [TsaqibController::class, 'laborPai'])->name('labor');
Route::get('/kegiatan', [TsaqibController::class, 'kegiatan'])->name('kegiatan');

// ==========================================================
// WAJIB LOGIN — Dashboard TSAQIB, Profile, Informasi Role
// (Bug #1 fix: profile.edit/update/destroy sebelumnya tidak ada sama
// sekali padahal dipanggil di navigation.blade.php — RouteNotFoundException
// di hampir semua halaman ber-x-app-layout untuk user yang login.
// Sekaligus benerin: /dashboard sebelumnya publik, padahal keputusan
// arsitektur bilang Dashboard TSAQIB wajib login.)
// ==========================================================
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [PageController::class, 'dashboard'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/role', [TsaqibController::class, 'role'])->name('role');
});


Route::prefix('admin-panel')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');           // Daftar Buku
    Route::get('/add', [AdminController::class, 'create'])->name('create');   // Form Tambah
    Route::post('/add', [AdminController::class, 'store'])->name('store');    // Simpan Data Baru
    Route::get('/{id}/edit', [AdminController::class, 'edit'])->name('edit');    // Form Edit
    Route::put('/{id}', [AdminController::class, 'update'])->name('update');     // Simpan Perubahan
    Route::delete('/{id}', [AdminController::class, 'destroy'])->name('destroy');// Hapus Data
});
require __DIR__.'/auth.php';
