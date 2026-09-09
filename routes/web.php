<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\OpenRecruitmentController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TsaqibController;
use App\Http\Controllers\VerificationController;
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

// Pusat Informasi: Berita + Buletin + Dokumentasi dalam satu halaman bertab (no reload).
Route::get('/info', [NewsController::class, 'info'])->name('info');

// Detail dokumentasi kegiatan (dicapai dari tab Dokumentasi di /info).
Route::get('/info/dokumentasi/{slug}', [NewsController::class, 'showDocumentation'])->name('info.dokumentasi.show');

// Alias lama /berita → /info (jaga-jaga ada link/bookmark lama). Tanpa nama
// agar route('info') jadi satu-satunya sumber URL berita di view.
Route::get('/berita', fn () => redirect()->route('info'));

// Detail berita per slug (dicapai dari tab Berita di /info & section Kabar Terbaru).
Route::get('/berita/{slug}', [NewsController::class, 'show'])->name('berita.show');

// Laboratorium PAI Publik + 3 sub-halaman (profil/modul/tugas).
// Modul & Tugas: akses penuh hanya siswa terverifikasi/guru — belum
// terverifikasi dirender dengan panel gate (bukan 403).
Route::get('/laboratorium-pai', [TsaqibController::class, 'laborPai'])->name('laboratorium.pai');
Route::get('/labor', [TsaqibController::class, 'laborPai'])->name('labor');
Route::get('/laboratorium-pai/profil', [TsaqibController::class, 'laborProfil'])->name('laboratorium.profil');
Route::get('/laboratorium-pai/modul', [TsaqibController::class, 'laborModul'])->name('laboratorium.modul');
Route::get('/laboratorium-pai/tugas', [TsaqibController::class, 'laborTugas'])->name('laboratorium.tugas');

// Open Recruitment Publik (Calon Anggota Kelas X). POST di-throttle: form publik
// tanpa login — tanpa limiter, bot bisa banjiri tabel registrations + email notif.
Route::get('/open-recruitment', [OpenRecruitmentController::class, 'showForm'])->name('open.recruitment');
Route::post('/open-recruitment', [OpenRecruitmentController::class, 'submit'])
    ->middleware('throttle:5,1')->name('open.recruitment.submit');
Route::get('/open-recruitment/terima-kasih', [OpenRecruitmentController::class, 'thankYou'])->name('open.recruitment.thank-you');

// Feed Komunitas — bisa dilihat oleh GUEST (tanpa login)
Route::get('/komunitas/post/{post}', [PostController::class, 'show'])->name('komunitas.post.show');
Route::get('/komunitas/{slug?}', [PageController::class, 'komunitasIndex'])->name('komunitas');
Route::get('/komunitas-show/{slug}', [PageController::class, 'komunitasShow'])->name('komunitas.show');

// Profil publik (read-only) — tamu (guest) BOLEH melihat profil user mana pun.
// Aksi tulis (edit/delete/follow) + tab/list "See All" tetap di balik auth di
// bawah. Constraint numeric pada {user} mencegah benturan dgn route literal.
Route::get('/profile/{user}', [ProfileController::class, 'show'])->name('profile.show')->where('user', '[0-9]+');

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

    // Post CRUD Actions — wajib login. Throttle terpisah: POST (create) lebih
    // ketat daripada update/delete milik sendiri.
    Route::post('/posts', [PostController::class, 'store'])
        ->middleware('throttle:20,1')->name('posts.store');
    Route::get('/komunitas/post/{post}/edit', [PostController::class, 'edit'])->name('komunitas.post.edit');
    Route::put('/komunitas/post/{post}', [PostController::class, 'update'])->name('komunitas.post.update');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');

    // Voting (upvote/downvote) — AJAX, mengembalikan JSON. Toggle pivot — aksi
    // ringan, limit longgar cukup untuk mencegah spam-bot tanpa mengganggu user.
    Route::post('/posts/{post}/vote', [PostController::class, 'vote'])
        ->middleware('throttle:60,1')->name('posts.vote');

    // Komentar & Repost — AJAX
    Route::post('/posts/{post}/comments', [PostController::class, 'storeComment'])
        ->middleware('throttle:20,1')->name('posts.comments.store');
    Route::delete('/comments/{comment}', [PostController::class, 'destroyComment'])->name('comments.destroy');
    Route::post('/posts/{post}/repost', [PostController::class, 'repost'])
        ->middleware('throttle:30,1')->name('posts.repost');

    // Simpan / batal simpan post (bookmark "Tersimpan") — AJAX. Pivot post_user,
    // sinkron antar perangkat & dipakai tab Tersimpan di profil.
    Route::post('/posts/{post}/save', [PostController::class, 'toggleSave'])
        ->middleware('throttle:60,1')->name('posts.save');

    // Lapor konten (post/comment) — AJAX
    Route::post('/reports', [ReportController::class, 'store'])
        ->middleware('throttle:10,1')->name('reports.store');

    // Verifikasi siswa 2 pintu (Pintu A: whitelist NISN; Pintu B: foto KTS →
    // approval admin). POST publik ber-login, di-throttle ketat.
    Route::view('/verifikasi-siswa', 'verifikasi.form')->name('verifikasi.siswa.form');
    Route::post('/verifikasi-siswa', [VerificationController::class, 'store'])
        ->middleware('throttle:5,1')->name('verifikasi.siswa');

    // Profil User
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Hapus foto profil kustom (upload/preset) → reset ke avatar default (AJAX, JSON).
    Route::delete('/profile/avatar', [ProfileController::class, 'destroyAvatar'])->name('profile.avatar.destroy');

    // Profil publik (user mana pun) + sistem follow + tab aktivitas. Constraint
    // numeric pada {user} mencegah benturan dengan route literal (mis. tidak ada).
    // CATATAN: profile.show (GET view) ada di blok publik di atas — tamu bisa lihat
    // profil (read-only). follow/unfollow/tabs/list tetap di sini (butuh login).
    Route::post('/profile/{user}/follow', [ProfileController::class, 'follow'])
        ->middleware('throttle:60,1')->name('profile.follow')->where('user', '[0-9]+');
    Route::delete('/profile/{user}/unfollow', [ProfileController::class, 'unfollow'])->name('profile.unfollow')->where('user', '[0-9]+');
    Route::get('/profile/{user}/followers', [ProfileController::class, 'followers'])->name('profile.followers')->where('user', '[0-9]+');
    Route::get('/profile/{user}/following', [ProfileController::class, 'following'])->name('profile.following')->where('user', '[0-9]+');
    Route::get('/profile/{user}/tabs/{tab}', [ProfileController::class, 'tabs'])->name('profile.tabs')->where('user', '[0-9]+')->where('tab', '[a-z]+');
    Route::get('/profile/{user}/list/{tab}', [ProfileController::class, 'list'])->name('profile.list')->where('user', '[0-9]+')->where('tab', '[a-z]+');

    Route::get('/role', [TsaqibController::class, 'role'])->name('role');

    // Perpustakaan — toggle bookmark buku ke "My Collection" / "Saved" (AJAX, JSON).
    // GET /perpustakaan tetap publik; hanya aksi simpan yang butuh login.
    Route::post('/perpustakaan/books/{book}/toggle', [LibraryController::class, 'toggleSave'])->name('perpustakaan.toggle');

    // Admin Panel (Proteksi Admin Role). Middleware 'admin' menutup seluruh
    // group di lapisan routing — method controller baru otomatis terlindungi
    // walau lupa memanggil checkAdmin().
    Route::prefix('admin-panel')->name('admin.')->middleware('admin')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('index');
        // Paginasi AJAX tiap list (HTML satu halaman + metadata, JSON).
        Route::get('/list/{resource}', [AdminController::class, 'list'])->name('list');
        Route::post('/users/{user}/role', [AdminController::class, 'updateUserRole'])->name('users.role');
        Route::post('/books', [AdminController::class, 'storeBook'])->name('books.store');
        Route::put('/books/{book}', [AdminController::class, 'updateBook'])->name('books.update');
        Route::delete('/books/{book}', [AdminController::class, 'destroyBook'])->name('books.destroy');
        Route::post('/news', [AdminController::class, 'storeNews'])->name('news.store');
        Route::put('/news/{news}', [AdminController::class, 'updateNews'])->name('news.update');
        Route::delete('/news/{news}', [AdminController::class, 'destroyNews'])->name('news.destroy');
        Route::post('/documentations', [AdminController::class, 'storeDocumentation'])->name('documentations.store');
        Route::delete('/documentations/{documentation}', [AdminController::class, 'destroyDocumentation'])->name('documentations.destroy');
        // Modul & Tugas (guru) + resolusi verifikasi siswa (blueprint RBAC).
        Route::post('/verifications/{verification}/resolve', [VerificationController::class, 'resolve'])->name('verifications.resolve');
        Route::post('/moduls', [AdminController::class, 'storeModul'])->name('moduls.store');
        Route::delete('/moduls/{modul}', [AdminController::class, 'destroyModul'])->name('moduls.destroy');
        Route::post('/labor-documents', [AdminController::class, 'updateLaborDocuments'])->name('labor-documents.update');
        Route::delete('/labor-documents/{type}', [AdminController::class, 'deleteLaborDocument'])->name('labor-documents.destroy');
        Route::post('/tugas', [AdminController::class, 'storeTugas'])->name('tugas.store');
        Route::delete('/tugas/{tugas}', [AdminController::class, 'destroyTugas'])->name('tugas.destroy');
        Route::post('/gurus', [AdminController::class, 'storeGuru'])->name('gurus.store');
        Route::put('/gurus/{guru}', [AdminController::class, 'updateGuru'])->name('gurus.update');
        Route::delete('/gurus/{guru}', [AdminController::class, 'destroyGuru'])->name('gurus.destroy');
        // Whitelist NISN (Pintu A verifikasi siswa) — tambah manual + import massal CSV/Excel.
        Route::post('/nisn-whitelist', [AdminController::class, 'storeNisnWhitelist'])->name('nisn-whitelist.store');
        Route::post('/nisn-whitelist/import', [AdminController::class, 'importNisnWhitelist'])->name('nisn-whitelist.import');
        Route::delete('/nisn-whitelist/{whitelist}', [AdminController::class, 'destroyNisnWhitelist'])->name('nisn-whitelist.destroy');
        Route::post('/toggle-recruitment', [AdminController::class, 'toggleRecruitment'])->name('toggle-recruitment');
        Route::post('/reports/{report}/resolve', [ReportController::class, 'resolve'])->name('reports.resolve');
    });

});

require __DIR__.'/auth.php';
