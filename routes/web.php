<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\PageController;
<<<<<<< HEAD
use Illuminate\Support\Facades\Route;
=======
use App\Http\Controllers\TsaqibController;
>>>>>>> 25129578b719ececc4ec024efeac171d589095bf

// Publik — tanpa login (Peta & Pustaka FSI)
Route::get('/', [PageController::class, 'home'])->name('home');
<<<<<<< HEAD
Route::get('/community', [PageController::class, 'community'])->name('community');
Route::get('/dashboard', [PageController::class, 'dashboard'])->name('dashboard');
=======
>>>>>>> 25129578b719ececc4ec024efeac171d589095bf
Route::get('/perpustakaan', [PageController::class, 'perpustakaan'])->name('perpustakaan');

<<<<<<< HEAD
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
=======
// TODO: konfirmasi ke tim — apakah route ini masih dipakai atau digabung ke dashboard TSAQIB.
Route::get('/community', [PageController::class, 'community'])->name('community');

// TSAQIB — wajib login
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware('verified')->name('dashboard');

    Route::get('/labor-pai', [TsaqibController::class, 'laborPai'])->name('labor-pai');
>>>>>>> 25129578b719ececc4ec024efeac171d589095bf
});

require __DIR__.'/auth.php';
