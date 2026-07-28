<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/community', [PageController::class, 'community'])->name('community');
Route::get('/labor', [PageController::class, 'labor'])->name('labor');
Route::get('/perpustakaan', [PageController::class, 'perpustakaan'])->name('perpustakaan');

Route::get('/', function () {
    return view('welcome');
});
