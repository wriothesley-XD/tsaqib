<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\OpenRecruitmentController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TsaqibController;
use Illuminate\Support\Facades\Route;

// ==========================================================
// 1. PUBLIC ROUTES (Dapat diakses publik tanpa login)
// ==========================================================
Route::get('/', [PageController::class, 'landing'])->name('landing');

// Halaman Hub Masjid (Pintu Masuk Laboratorium PAI & Open Recruitment)
Route::get('/hub', [PageController::class, 'hub'])->name('hub');
Route::get('/hub-masjid', [PageController::class, 'hub'])->name('hub.masjid');

// Perpustakaan Digital Publik (filter ?category= & ?q= + pagination server-side)
Route::get('/perpustakaan', [LibraryController::class, 'index'])->name('perpustakaan');

// Laboratorium PAI Publik
Route::get('/laboratorium-pai', [TsaqibController::class, 'laborPai'])->name('laboratorium.pai');
Route::get('/labor', [TsaqibController::class, 'laborPai'])->name('labor');

// Open Recruitment Publik (Calon Anggota Kelas X)
Route::get('/open-recruitment', [OpenRecruitmentController::class, 'showForm'])->name('open.recruitment');
Route::post('/open-recruitment', [OpenRecruitmentController::class, 'submit'])->name('open.recruitment.submit');
Route::get('/open-recruitment/terima-kasih', [OpenRecruitmentController::class, 'thankYou'])->name('open.recruitment.thank-you');

// Feed Komunitas — bisa dilihat oleh GUEST (tanpa login)
Route::get('/komunitas/{slug?}', [PageController::class, 'komunitasIndex'])->name('komunitas');
Route::get('/komunitas-show/{slug}', [PageController::class, 'komunitasShow'])->name('komunitas.show');

// ==========================================================
// 2. TSAQIB MAIN EXPERIENCE (Wajib Login / Check Auth)
// ==========================================================
Route::middleware('auth')->group(function () {

    // Halaman Pemilihan Role Karakter Komunitas (Slider Carousel & Store ke DB)
    Route::get('/select-role', [PageController::class, 'selectRole'])->name('select-role');
    Route::post('/select-role', [PageController::class, 'storeRole'])->name('select-role.store');

    // Beranda TSAQIB (Redirect ke Komunitas)
    Route::get('/beranda', [PageController::class, 'beranda'])->name('beranda');
    Route::get('/dashboard', [PageController::class, 'beranda'])->name('dashboard');

    // Post CRUD Actions — wajib login
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');

    // Profil User
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/role', [TsaqibController::class, 'role'])->name('role');

    // Admin Panel (Proteksi Admin Role)
    Route::prefix('admin-panel')->name('admin.')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('index');
        Route::post('/users/{user}/role', [AdminController::class, 'updateUserRole'])->name('users.role');
        Route::post('/books', [AdminController::class, 'storeBook'])->name('books.store');
        Route::delete('/books/{book}', [AdminController::class, 'destroyBook'])->name('books.destroy');
        Route::post('/toggle-recruitment', [AdminController::class, 'toggleRecruitment'])->name('toggle-recruitment');
    });

});

require __DIR__.'/auth.php';