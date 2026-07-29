<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\TsaqibController;

// Publik — tanpa login (Peta & Pustaka FSI)
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/perpustakaan', [PageController::class, 'perpustakaan'])->name('perpustakaan');

// TODO: konfirmasi ke tim — apakah route ini masih dipakai atau digabung ke dashboard TSAQIB.
Route::get('/community', [PageController::class, 'community'])->name('community');

// TSAQIB — wajib login
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware('verified')->name('dashboard');

    Route::get('/labor-pai', [TsaqibController::class, 'laborPai'])->name('labor-pai');
});

require __DIR__.'/auth.php';
