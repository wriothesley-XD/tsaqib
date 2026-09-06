<?php

namespace App\Http\Controllers;

use App\Models\ActivityDocumentation;
use App\Models\Book;
use App\Models\GuruProfile;
use App\Models\Modul;
use App\Models\News;
use App\Models\NisnWhitelist;
use App\Models\Post;
use App\Models\Registration;
use App\Models\Report;
use App\Models\Setting;
use App\Models\Tugas;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;

class AdminController extends Controller
{
    /**
     * Memeriksa dan memastikan pengguna memiliki hak akses Admin.
     *
     * SECURITY FIX: logic lama di sini otomatis menaikkan role user manapun
     * yang login dengan email 'test1@gmail.com' atau 'admin@fsi.sch.id' jadi
     * 'admin'. Karena pendaftaran akun terbuka untuk publik (lihat
     * RegisteredUserController), siapa pun bisa mendaftar pakai email
     * tersebut dan langsung mendapat akses admin penuh — ini backdoor, bukan
     * fitur. Logic auto-assign sudah dihapus; role admin sekarang murni
     * dibaca dari kolom `role` di database (ditetapkan manual/lewat seeder).
     */
    private function checkAdmin(): void
    {
        $user = Auth::user();

        if (! $user) {
            abort(401, 'Anda harus login terlebih dahulu.');
        }

        if (! in_array($user->role, ['admin', 'guru'], true)) {
            abort(403, 'Akses Ditolak. Halaman ini hanya dapat diakses oleh Admin & Guru.');
        }
    }

    /**
     * Dashboard Admin Panel Sederhana.
     * Route: GET /admin-panel
     */
    public function index(Request $request)
    {
        $this->checkAdmin();

        $perPage = 10;

        // Semua list dimuat sebagai paginator (per halaman) — BUKAN ->get() lagi.
        // Tab hanya merender halaman ke-1; halaman berikutnya di-fetch via AJAX
        // (AdminController::list) saat user pindah halaman atau pakai "View All".
        $users = User::latest()->paginate($perPage);
        $books = Book::latest()->paginate($perPage);

        // Filter komunitas pada tab kelola postingan
        $selectedCommunity = $request->query('community');
        $postsQuery = Post::with('user');
        if ($selectedCommunity && $selectedCommunity !== 'all' && $selectedCommunity !== 'semua') {
            $postsQuery->where('community_slug', $selectedCommunity);
        }
        $posts = $postsQuery->latest()->paginate($perPage)->withQueryString();

        $registrations = Registration::latest()->paginate($perPage);
        $news = News::with('user')->latest()->paginate($perPage);
        $documentations = ActivityDocumentation::with('photos')->latest()->paginate($perPage);
        $nisnWhitelist = NisnWhitelist::latest()->paginate($perPage);
        $moduls = Modul::with('user')->latest()->paginate($perPage);
        $isRecruitmentOpen = Setting::getByKey('recruitment_open', '1') === '1';

        // Pengaturan Dokumen & Struktur Laboratorium PAI
        $laborSettings = [
            'struktur_organisasi_pembina' => Setting::getByKey('struktur_organisasi_pembina'),
            'struktur_organisasi_siswa' => Setting::getByKey('struktur_organisasi_siswa'),
            'profil_buku_tsaqib_url' => Setting::getByKey('profil_buku_tsaqib_url', 'https://heyzine.com/flip-book/bdf3f31765.html'),
            'profil_buku_tsaqib_pdf' => Setting::getByKey('profil_buku_tsaqib_pdf'),
            'monev_internal_pdf' => Setting::getByKey('monev_internal_pdf'),
            'monev_internal_url' => Setting::getByKey('monev_internal_url'),
        ];

        // Laporan konten pending (untuk badge + Perlu Perhatian + tab Laporan).
        $laporan = Report::pending()->with(['reportable.user', 'reporter'])->latest()->paginate($perPage);
        $pendingReportCount = $laporan->total();

        // Feed "Perlu Perhatian" di dashboard: 5 laporan terbaru dari halaman aktif.
        $perluPerhatian = $laporan->getCollection()->take(5);

        $stats = [
            'total_users' => $users->total(),
            'total_posts' => Post::count(),
            'total_books' => $books->total(),
            'total_registrations' => $registrations->total(),
            'total_news' => $news->total(),
            'total_moduls' => $moduls->total(),
        ];

        return view('admin.index', compact('users', 'books', 'posts', 'selectedCommunity', 'registrations', 'news', 'documentations', 'nisnWhitelist', 'moduls', 'laborSettings', 'isRecruitmentOpen', 'stats', 'laporan', 'pendingReportCount', 'perluPerhatian'));
    }

    /**
     * Endpoint AJAX untuk paginasi tiap list admin.
     * Mengembalikan HTML baris/kartu satu halaman + metadata paginasi sebagai JSON.
     * Route: GET /admin-panel/list/{resource}?page=N
     */
    public function list(Request $request, string $resource)
    {
        $this->checkAdmin();
        $perPage = 10;

        // itemView/itemVar/groupField HANYA untuk list berbasis kartu (Posts, Laporan).
        // Di mode "Lihat Semua" mereka dikelompokkan per kategori (groupField) agar
        // bukan sekadar daftar datar panjang. List tabel (Users/Books/Registrations)
        // tetap html-only — tabel sudah terstruktur.
        $map = [
            'users' => ['view' => 'admin._list_users',         'query' => User::latest()],
            'books' => ['view' => 'admin._list_books',         'query' => Book::latest(), 'search' => ['title', 'author', 'category']],
            'news' => ['view' => 'admin._list_news',          'query' => News::with('user')->latest()],
            'documentations' => ['view' => 'admin._list_documentations', 'query' => ActivityDocumentation::with('photos')->latest()],
            'posts' => ['view' => 'admin._list_posts',         'query' => Post::with('user')->latest(),         'itemView' => 'admin._post_item',   'itemVar' => 'post', 'groupField' => 'community_slug'],
            'registrations' => ['view' => 'admin._list_registrations', 'query' => Registration::latest()],
            'laporan' => ['view' => 'admin._list_laporan',       'query' => Report::pending()->with(['reportable.user', 'reporter'])->latest(), 'itemView' => 'admin._laporan_item', 'itemVar' => 'r', 'groupField' => 'reportable_type'],
            'nisn_whitelist' => ['view' => 'admin._list_nisn_whitelist', 'query' => NisnWhitelist::latest(), 'search' => ['nisn', 'nama', 'kelas']],
            'moduls' => ['view' => 'admin._list_moduls', 'query' => Modul::with('user')->latest(), 'search' => ['judul', 'kategori', 'target_kelas', 'deskripsi']],
        ];

        if (! isset($map[$resource])) {
            abort(404);
        }
        $cfg = $map[$resource];

        $query = $cfg['query'];

        // Filter khusus komunitas pada resource posts
        if ($resource === 'posts') {
            $community = $request->query('community');
            if ($community && $community !== 'all' && $community !== 'semua') {
                $query->where('community_slug', $community);
            }
        }

        // Filter pencarian teks (?q=) — hanya resource yang mendeklarasikan 'search';
        // dicocokkan via LIKE pada salah satu field (OR). Lihat [data-admin-search]
        // di resources/views/admin/_tab_books.blade.php.
        $search = trim((string) $request->query('q', ''));
        if ($search !== '' && ! empty($cfg['search'])) {
            $query->where(function ($sub) use ($cfg, $search) {
                foreach ($cfg['search'] as $i => $field) {
                    $i === 0
                        ? $sub->where($field, 'like', '%'.$search.'%')
                        : $sub->orWhere($field, 'like', '%'.$search.'%');
                }
            });
        }

        $paginator = $query->paginate($perPage);
        $html = view($cfg['view'], [
            $resource => $paginator,
            'startIndex' => $paginator->firstItem() ?? 1,
            'search' => $search,   // dipakai partial untuk pesan kosong yang kontekstual
        ])->render();

        // Bangun array items {group, html} untuk list berkartu — dipakai JS di mode
        // "Lihat Semua" untuk memasukkan tiap kartu ke kelompok kategorinya.
        $items = null;
        if (! empty($cfg['itemView'])) {
            $field = $cfg['groupField'];
            $items = $paginator->getCollection()->map(function ($model) use ($cfg, $field) {
                return [
                    'group' => $model->{$field} ?: 'lainnya',
                    'html' => view($cfg['itemView'], [$cfg['itemVar'] => $model])->render(),
                ];
            })->values();
        }

        return response()->json([
            'html' => $html,
            'items' => $items,
            'currentPage' => $paginator->currentPage(),
            'lastPage' => $paginator->lastPage(),
            'total' => $paginator->total(),
            'perPage' => $paginator->perPage(),
            'from' => $paginator->firstItem(),
            'to' => $paginator->lastItem(),
        ]);
    }

    /**
     * Ubah role pengguna (admin/member).
     * Route: POST /admin-panel/users/{user}/role
     */
    public function updateUserRole(Request $request, User $user): RedirectResponse
    {
        $this->checkAdmin();

        $validated = $request->validate([
            'role' => ['required', 'string', 'in:admin,member'],
        ]);

        $user->update(['role' => $validated['role']]);

        return redirect()->back()->with('success', 'Role pengguna '.$user->name.' berhasil diperbarui!');
    }

    /**
     * Tambah Buku PDF Perpustakaan Baru.
     * Route: POST /admin-panel/books
     */
    public function storeBook(Request $request): RedirectResponse
    {
        $this->checkAdmin();

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:2000'],
            'cover' => ['nullable', 'image', 'max:2048'],
            'pdf' => ['required', 'file', 'mimes:pdf', 'max:20480'],
            'is_visible' => ['nullable', 'boolean'],
        ]);

        $coverPath = null;
        if ($request->hasFile('cover')) {
            $coverPath = $request->file('cover')->store('covers', 'public');
        }

        $pdfPath = $request->file('pdf')->store('books', 'public');

        Book::create([
            'title' => $validated['title'],
            'author' => $validated['author'],
            'category' => $validated['category'],
            'description' => $validated['description'] ?? null,
            'cover_image' => $coverPath,
            'pdf_path' => $pdfPath,
            'is_visible' => $request->has('is_visible'),
        ]);

        return redirect()->back()->with('success', 'Buku PDF baru berhasil ditambahkan ke Perpustakaan!');
    }

    /**
     * Perbarui Buku PDF Perpustakaan (termasuk upload/ganti cover).
     * Cover & PDF bersifat opsional saat edit: kosongkan untuk mempertahankan
     * file yang sudah ada. Route: PUT /admin-panel/books/{book}
     */
    public function updateBook(Request $request, Book $book): RedirectResponse
    {
        $this->checkAdmin();

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:2000'],
            'cover' => ['nullable', 'image', 'max:2048'],
            'pdf' => ['nullable', 'file', 'mimes:pdf', 'max:20480'],
        ]);

        // Ganti cover bila diunggah; jika tidak, pertahankan yang ada.
        if ($request->hasFile('cover')) {
            if ($book->cover_image && Storage::disk('public')->exists($book->cover_image)) {
                Storage::disk('public')->delete($book->cover_image);
            }
            $book->cover_image = $request->file('cover')->store('covers', 'public');
        }

        // Ganti PDF bila diunggah; jika tidak, pertahankan yang ada.
        if ($request->hasFile('pdf')) {
            if ($book->pdf_path && Storage::disk('public')->exists($book->pdf_path)) {
                Storage::disk('public')->delete($book->pdf_path);
            }
            $book->pdf_path = $request->file('pdf')->store('books', 'public');
        }

        $book->title = $validated['title'];
        $book->author = $validated['author'];
        $book->category = $validated['category'];
        $book->description = $validated['description'] ?? null;
        $book->is_visible = $request->has('is_visible');
        $book->save();

        return redirect()->back()->with('success', 'Buku berhasil diperbarui!');
    }

    /**
     * Hapus Buku PDF Perpustakaan.
     * Route: DELETE /admin-panel/books/{book}
     */
    public function destroyBook(Book $book): RedirectResponse
    {
        $this->checkAdmin();

        if ($book->cover_image && Storage::disk('public')->exists($book->cover_image)) {
            Storage::disk('public')->delete($book->cover_image);
        }
        if ($book->pdf_path && Storage::disk('public')->exists($book->pdf_path)) {
            Storage::disk('public')->delete($book->pdf_path);
        }

        $book->delete();

        return redirect()->back()->with('success', 'Buku berhasil dihapus dari Perpustakaan!');
    }

    /**
     * Validasi & persiapan payload bersama untuk create/update berita.
     * Slug diisi otomatis dari judul bila kosong, lalu dijamin unik.
     */
    private function validateNews(Request $request, ?int $ignoreId = null): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'content' => ['required', 'string'],
            'thumbnail' => ['nullable', 'image', 'max:4096'],
            'published_at' => ['nullable', 'string'],
        ]);

        // datetime-local mengirim "Y-m-d\TH:i" → MySQL butuh "Y-m-d H:i".
        if (! empty($data['published_at'])) {
            $data['published_at'] = str_replace('T', ' ', $data['published_at']);
        }

        $data['slug'] = $this->uniqueNewsSlug(
            $data['slug'] ? Str::slug($data['slug']) : Str::slug($data['title']),
            $ignoreId
        );

        return $data;
    }

    /**
     * Menjamin slug unik: tambahkan suffix -2, -3, dst. bila sudah dipakai.
     */
    private function uniqueNewsSlug(string $slug, ?int $ignoreId = null): string
    {
        $base = $slug ?: 'berita';
        $candidate = $base;
        $i = 2;
        while (News::where('slug', $candidate)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $candidate = $base.'-'.$i++;
        }

        return $candidate;
    }

    /**
     * Tambah berita baru.
     * Route: POST /admin-panel/news
     */
    public function storeNews(Request $request): RedirectResponse
    {
        $this->checkAdmin();

        $data = $this->validateNews($request);

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('news', 'public');
        }

        $data['user_id'] = Auth::id();
        $data['excerpt'] = $data['excerpt'] ?? Str::limit(strip_tags($data['content']), 160);

        News::create($data);

        return redirect()->back()->with('success', 'Berita baru berhasil diterbitkan!');
    }

    /**
     * Perbarui berita.
     * Route: PUT /admin-panel/news/{news}
     */
    public function updateNews(Request $request, News $news): RedirectResponse
    {
        $this->checkAdmin();

        $data = $this->validateNews($request, $news->id);

        if ($request->hasFile('thumbnail')) {
            if ($news->thumbnail && Storage::disk('public')->exists($news->thumbnail)) {
                Storage::disk('public')->delete($news->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('news', 'public');
        }

        if (empty($data['excerpt'])) {
            $data['excerpt'] = Str::limit(strip_tags($data['content']), 160);
        }

        $news->update($data);

        return redirect()->back()->with('success', 'Berita berhasil diperbarui!');
    }

    /**
     * Hapus berita.
     * Route: DELETE /admin-panel/news/{news}
     */
    public function destroyNews(News $news): RedirectResponse
    {
        $this->checkAdmin();

        if ($news->thumbnail && Storage::disk('public')->exists($news->thumbnail)) {
            Storage::disk('public')->delete($news->thumbnail);
        }

        $news->delete();

        return redirect()->back()->with('success', 'Berita berhasil dihapus!');
    }

    /**
     * Simpan dokumentasi kegiatan baru + upload banyak foto sekaligus.
     * Validasi: wajib ada foto; per foto max 5MB, hanya jpg/png/webp.
     * Route: POST /admin-panel/documentations
     */
    public function storeDocumentation(Request $request): RedirectResponse
    {
        $this->checkAdmin();

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'event_date' => 'nullable|date',
            'category' => 'nullable|string|max:50',
            'photos' => 'required|array|min:1',
            'photos.*' => 'file|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        // Slug unik dari judul (pola uniqueNewsSlug).
        $base = Str::slug($data['title']);
        $slug = $base;
        $attempt = 1;
        while (ActivityDocumentation::where('slug', $slug)->exists()) {
            $slug = $base.'-'.(++$attempt);
        }

        $doc = ActivityDocumentation::create([
            'title' => $data['title'],
            'slug' => $slug,
            'description' => $data['description'] ?? null,
            'event_date' => $data['event_date'] ?? null,
            'category' => $data['category'] ?? null,
        ]);

        foreach ($request->file('photos') as $photo) {
            $doc->photos()->create([
                'image_path' => $photo->store('documentations', 'public'),
            ]);
        }

        return redirect()->back()->with('success', 'Dokumentasi kegiatan berhasil ditambahkan!');
    }

    /**
     * Hapus dokumentasi kegiatan + file fotonya (baris foto terhapus via FK cascade).
     * Route: DELETE /admin-panel/documentations/{documentation}
     */
    public function destroyDocumentation(ActivityDocumentation $documentation): RedirectResponse
    {
        $this->checkAdmin();

        Storage::disk('public')->delete(
            $documentation->photos()->pluck('image_path')->all()
        );

        $documentation->delete();

        return redirect()->back()->with('success', 'Dokumentasi kegiatan berhasil dihapus!');
    }

    /**
     * Simpan modul/silabus pembelajaran (PDF/DOCX) — guru/admin.
     * Route: POST /admin-panel/moduls
     */
    public function storeModul(Request $request): RedirectResponse
    {
        $this->checkAdmin();

        $data = $request->validate([
            'judul' => 'required|string|max:255',
            'kategori' => 'nullable|string|max:100',
            'target_kelas' => 'nullable|in:X,XI,XII,Semua',
            'deskripsi' => 'nullable|string|max:5000',
            'file' => 'required|file|mimes:pdf,docx,doc|max:25600', // 25MB
        ]);

        $data['file_path'] = $request->file('file')->store('moduls', 'public');
        $data['user_id'] = $request->user()->id;
        unset($data['file']);

        Modul::create($data);

        return redirect()->back()->with('success', 'Materi silabus/modul berhasil diupload!');
    }

    /**
     * Update dokumen publikasi & infografis Laboratorium PAI.
     * Route: POST /admin-panel/labor-documents
     */
    public function updateLaborDocuments(Request $request): RedirectResponse
    {
        $this->checkAdmin();

        $request->validate([
            'profil_buku_tsaqib_url' => 'nullable|url|max:500',
            'profil_buku_tsaqib_pdf' => 'nullable|file|mimes:pdf|max:30720',
            'monev_internal_url' => 'nullable|url|max:500',
            'monev_internal_pdf' => 'nullable|file|mimes:pdf|max:30720',
            'struktur_organisasi_pembina' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'struktur_organisasi_siswa' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
        ]);

        if ($request->filled('profil_buku_tsaqib_url')) {
            Setting::setByKey('profil_buku_tsaqib_url', $request->input('profil_buku_tsaqib_url'));
        }

        if ($request->hasFile('profil_buku_tsaqib_pdf')) {
            $oldPath = Setting::getByKey('profil_buku_tsaqib_pdf');
            if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }
            $path = $request->file('profil_buku_tsaqib_pdf')->store('labor-docs', 'public');
            Setting::setByKey('profil_buku_tsaqib_pdf', $path);
        }

        if ($request->filled('monev_internal_url')) {
            Setting::setByKey('monev_internal_url', $request->input('monev_internal_url'));
        }

        if ($request->hasFile('monev_internal_pdf')) {
            $oldPath = Setting::getByKey('monev_internal_pdf');
            if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }
            $path = $request->file('monev_internal_pdf')->store('labor-docs', 'public');
            Setting::setByKey('monev_internal_pdf', $path);
        }

        if ($request->hasFile('struktur_organisasi_pembina')) {
            $oldPath = Setting::getByKey('struktur_organisasi_pembina');
            if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }
            $path = $request->file('struktur_organisasi_pembina')->store('labor-docs', 'public');
            Setting::setByKey('struktur_organisasi_pembina', $path);
        }

        if ($request->hasFile('struktur_organisasi_siswa')) {
            $oldPath = Setting::getByKey('struktur_organisasi_siswa');
            if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }
            $path = $request->file('struktur_organisasi_siswa')->store('labor-docs', 'public');
            Setting::setByKey('struktur_organisasi_siswa', $path);
        }

        return redirect()->back()->with('success', 'Dokumen & infografis Laboratorium PAI berhasil diperbarui!');
    }

    /**
     * Hapus berkas kustom dokumen Laboratorium PAI (reset ke default).
     * Route: DELETE /admin-panel/labor-documents/{type}
     */
    public function deleteLaborDocument(string $type): RedirectResponse
    {
        $this->checkAdmin();

        $allowed = [
            'struktur_organisasi_pembina',
            'struktur_organisasi_siswa',
            'profil_buku_tsaqib_pdf',
            'monev_internal_pdf',
            'monev_internal_url',
        ];

        if (! in_array($type, $allowed, true)) {
            abort(404);
        }

        $path = Setting::getByKey($type);
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }

        Setting::setByKey($type, null);

        return redirect()->back()->with('success', 'Berkas berhasil dihapus dan dikembalikan ke default.');
    }

    /**
     * Hapus modul + file PDF-nya.
     * Route: DELETE /admin-panel/moduls/{modul}
     */
    public function destroyModul(Modul $modul): RedirectResponse
    {
        $this->checkAdmin();

        if ($modul->file_path && Storage::disk('public')->exists($modul->file_path)) {
            Storage::disk('public')->delete($modul->file_path);
        }

        $modul->delete();

        return redirect()->back()->with('success', 'Modul berhasil dihapus!');
    }

    /**
     * Simpan tugas + link Google Classroom — guru/admin.
     * Route: POST /admin-panel/tugas
     */
    public function storeTugas(Request $request): RedirectResponse
    {
        $this->checkAdmin();

        Tugas::create($request->validate([
            'judul_tugas' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:5000',
            'deadline' => 'nullable|date',
            'target_kelas' => 'nullable|in:X,XI,XII',
            'link_google_classroom' => ['nullable', 'url', 'regex:#^https://classroom\.google\.com/#'],
        ]) + ['user_id' => $request->user()->id]);

        return redirect()->back()->with('success', 'Tugas berhasil ditambahkan!');
    }

    /**
     * Hapus tugas.
     * Route: DELETE /admin-panel/tugas/{tugas}
     */
    public function destroyTugas(Tugas $tugas): RedirectResponse
    {
        $this->checkAdmin();

        $tugas->delete();

        return redirect()->back()->with('success', 'Tugas berhasil dihapus!');
    }

    /**
     * Simpan profil guru (untuk halaman publik Profil & Guru).
     * kelas_diampu dikirim sebagai multiple select/checkbox array.
     * Route: POST /admin-panel/gurus
     */
    public function storeGuru(Request $request): RedirectResponse
    {
        $this->checkAdmin();

        $data = $request->validate([
            'user_id' => 'required|exists:users,id|unique:guru_profiles,user_id',
            'nip' => 'nullable|string|max:30',
            'mapel_pengampu' => 'nullable|string|max:100',
            'kelas_diampu' => 'nullable|array',
            'kelas_diampu.*' => 'in:X,XI,XII',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'wa_number' => 'nullable|string|max:20',
        ]);

        $data['foto_path'] = $request->hasFile('foto')
            ? $request->file('foto')->store('guru', 'public')
            : null;
        unset($data['foto']);

        GuruProfile::create($data);

        return redirect()->back()->with('success', 'Profil guru berhasil ditambahkan!');
    }

    /**
     * Hapus profil guru + foto-nya.
     * Route: DELETE /admin-panel/gurus/{guru}
     */
    public function destroyGuru(GuruProfile $guru): RedirectResponse
    {
        $this->checkAdmin();

        if ($guru->foto_path && Storage::disk('public')->exists($guru->foto_path)) {
            Storage::disk('public')->delete($guru->foto_path);
        }

        $guru->delete();

        return redirect()->back()->with('success', 'Profil guru berhasil dihapus!');
    }

    /**
     * Buka/Tutup Sakelar Open Recruitment.
     * Route: POST /admin-panel/toggle-recruitment
     */
    /**
     * Tambah satu NISN / NIS manual ke whitelist.
     * Route: POST /admin-panel/nisn-whitelist
     */
    public function storeNisnWhitelist(Request $request): RedirectResponse
    {
        $this->checkAdmin();

        $data = $request->validate([
            'nisn' => 'required|string|max:20|unique:nisn_whitelist,nisn',
            'nis' => 'nullable|string|max:20',
            'nama' => 'nullable|string|max:100',
            'kelas' => 'nullable|string|max:20',
        ], [
            'nisn.unique' => 'NISN ini sudah ada di whitelist.',
        ]);

        $data['nisn'] = preg_replace('/\D/', '', $data['nisn']);
        if (! empty($data['nis'])) {
            $data['nis'] = preg_replace('/\D/', '', $data['nis']);
        }

        NisnWhitelist::create($data);

        return redirect()->back()->with('success', 'NISN / NIS berhasil ditambahkan ke whitelist.');
    }

    /**
     * Hapus satu NISN dari whitelist.
     * Route: DELETE /admin-panel/nisn-whitelist/{whitelist}
     */
    public function destroyNisnWhitelist(NisnWhitelist $whitelist): RedirectResponse
    {
        $this->checkAdmin();

        $whitelist->delete();

        return redirect()->back()->with('success', 'NISN dihapus dari whitelist.');
    }

    /**
     * Import massal NISN dari file CSV atau Excel (.xlsx/.xls).
     * Mendukung otomatis Format 8355 (Buku Induk) dan CSV umum:
     * - Mendeteksi letak header otomatis di baris manapun (baris 1, 14, dll).
     * - Mengenali kolom NISN, Nomor Induk (NIS), Nama Siswa, dan Kelas.
     * - Membaca .xlsx secara native tanpa dependensi eksternal.
     * Route: POST /admin-panel/nisn-whitelist/import
     */
    public function importNisnWhitelist(Request $request): RedirectResponse
    {
        $this->checkAdmin();

        $request->validate([
            'file' => 'required|file|mimes:csv,txt,xlsx,xls,zip|max:10240',
        ]);

        $file = $request->file('file');
        $extension = strtolower($file->getClientOriginalExtension());

        try {
            $rows = $this->parseNisnFile($file->getRealPath(), $extension);
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Gagal membaca file: '.$e->getMessage());
        }

        if (empty($rows)) {
            return redirect()->back()->with('error', 'File kosong atau tidak ada baris yang bisa dibaca.');
        }

        // Smart Header Scanner:
        // Pindai baris 0 sampai 30 untuk menemukan baris header (misal format 8355 Dapodik ada di baris 14).
        $headerRowIdx = null;
        $colNisn = null;
        $colNis = null;
        $colNama = null;
        $colKelas = null;

        foreach ($rows as $rIdx => $row) {
            $lowerRow = array_map(fn ($v) => strtolower(trim((string) $v)), $row);
            foreach ($lowerRow as $cIdx => $val) {
                if (str_contains($val, 'nisn')) {
                    $colNisn = $cIdx;
                    $headerRowIdx = $rIdx;
                } elseif ((str_contains($val, 'induk') || $val === 'nis' || str_contains($val, 'no. induk')) && ! str_contains($val, 'nisn')) {
                    $colNis = $cIdx;
                    $headerRowIdx = $rIdx;
                } elseif (str_contains($val, 'nama') && ! str_contains($val, 'orang tua') && ! str_contains($val, 'ayah') && ! str_contains($val, 'ibu')) {
                    $colNama = $cIdx;
                    $headerRowIdx = $rIdx;
                } elseif (str_contains($val, 'kelas') || str_contains($val, 'rombel')) {
                    $colKelas = $cIdx;
                }
            }
            if ($headerRowIdx !== null && ($colNisn !== null || $colNis !== null)) {
                break;
            }
        }

        if ($headerRowIdx !== null) {
            // Potong baris sebelum dan termasuk baris header
            $rows = array_slice($rows, $headerRowIdx + 1);
        } else {
            // Jika tidak ada header, default kolom: 0 = NISN, 1 = Nama, 2 = Kelas
            $colNisn = 0;
            $colNama = 1;
            $colKelas = 2;
        }

        $created = 0;
        $updated = 0;
        $skipped = 0;

        foreach ($rows as $row) {
            $rawNisn = ($colNisn !== null && isset($row[$colNisn])) ? (string) $row[$colNisn] : '';
            $rawNis = ($colNis !== null && isset($row[$colNis])) ? (string) $row[$colNis] : '';
            $rawNama = ($colNama !== null && isset($row[$colNama])) ? (string) $row[$colNama] : '';
            $rawKelas = ($colKelas !== null && isset($row[$colKelas])) ? (string) $row[$colKelas] : '';

            // Bersihkan NISN & NIS (ambil angka saja, hilangkan tanda petik Excel seperti '0118703733)
            $nisn = preg_replace('/\D/', '', $rawNisn);
            $nis = preg_replace('/\D/', '', $rawNis);
            $nama = trim($rawNama);
            $kelas = trim($rawKelas);

            // Lewati baris kosong atau baris penomoran sub-header (seperti "1, 2, 3, 4...")
            if ($nisn === '' && $nis === '') {
                $skipped++;

                continue;
            }
            if (strtolower($nama) === 'nama siswa' || (strlen($nisn) < 4 && strlen($nis) < 4)) {
                $skipped++;

                continue;
            }

            // Kunci unik: gunakan NISN bila ada, atau fallback NIS
            $lookupKey = $nisn !== '' ? ['nisn' => $nisn] : ['nisn' => 'NIS-'.$nis];

            $record = NisnWhitelist::updateOrCreate(
                $lookupKey,
                [
                    'nis' => $nis ?: null,
                    'nama' => $nama ?: null,
                    'kelas' => $kelas ?: null,
                ]
            );

            $record->wasRecentlyCreated ? $created++ : $updated++;
        }

        $message = "Import berhasil — {$created} siswa baru ditambahkan ke whitelist, {$updated} data diperbarui";
        $message .= $skipped > 0 ? ", {$skipped} baris kosong/header dilewati." : '.';

        return redirect()->back()->with('success', $message);
    }

    /**
     * Baca file NISN jadi array baris. CSV/TXT dibaca native.
     * XLSX dibaca native via ZipArchive & SimpleXML tanpa perlu phpspreadsheet.
     */
    private function parseNisnFile(string $path, string $extension): array
    {
        if (in_array($extension, ['csv', 'txt'], true)) {
            return $this->parseNisnCsv($path);
        }

        if (in_array($extension, ['xlsx', 'xls', 'zip'], true)) {
            if (class_exists(IOFactory::class)) {
                $spreadsheet = IOFactory::load($path);

                return $spreadsheet->getActiveSheet()->toArray(null, true, true, false);
            }

            // Fallback native parser bertenaga ZipArchive + SimpleXML
            return $this->parseNisnXlsxNative($path);
        }

        throw new \RuntimeException('Format file tidak dikenali. Gunakan file .xlsx atau .csv.');
    }

    /**
     * Parser native XLSX tanpa package eksternal bertenaga ZipArchive & SimpleXML.
     */
    private function parseNisnXlsxNative(string $path): array
    {
        $zip = new \ZipArchive;
        if ($zip->open($path) !== true) {
            throw new \RuntimeException('Berkas Excel (.xlsx) tidak dapat dibuka atau rusak.');
        }

        // 1. Ekstrak shared strings jika ada
        $sharedStrings = [];
        if (($idx = $zip->locateName('xl/sharedStrings.xml')) !== false) {
            $xmlStr = $zip->getFromIndex($idx);
            $sXml = @simplexml_load_string($xmlStr);
            if ($sXml && isset($sXml->si)) {
                foreach ($sXml->si as $si) {
                    if (isset($si->t)) {
                        $sharedStrings[] = (string) $si->t;
                    } elseif (isset($si->r)) {
                        $txt = '';
                        foreach ($si->r as $r) {
                            $txt .= (string) $r->t;
                        }
                        $sharedStrings[] = $txt;
                    } else {
                        $sharedStrings[] = '';
                    }
                }
            }
        }

        // 2. Cari worksheet pertama
        $sheetXmlContent = null;
        if (($idx = $zip->locateName('xl/worksheets/sheet1.xml')) !== false) {
            $sheetXmlContent = $zip->getFromIndex($idx);
        } else {
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $name = $zip->getNameIndex($i);
                if (preg_match('#xl/worksheets/sheet\d+\.xml#i', $name)) {
                    $sheetXmlContent = $zip->getFromIndex($i);
                    break;
                }
            }
        }

        $rows = [];
        if ($sheetXmlContent) {
            $sheetXml = @simplexml_load_string($sheetXmlContent);
            if ($sheetXml && isset($sheetXml->sheetData->row)) {
                foreach ($sheetXml->sheetData->row as $rowEl) {
                    $row = [];
                    $curCol = 0;
                    foreach ($rowEl->c as $cell) {
                        $cellRef = (string) $cell['r'];
                        // Konversi notasi kolom A1, B1, C14 menjadi indeks 0-based
                        if (preg_match('/^([A-Z]+)(\d+)$/', $cellRef, $m)) {
                            $letters = $m[1];
                            $targetCol = 0;
                            for ($k = 0; $k < strlen($letters); $k++) {
                                $targetCol = $targetCol * 26 + (ord($letters[$k]) - ord('A') + 1);
                            }
                            $targetCol -= 1;
                            while ($curCol < $targetCol) {
                                $row[$curCol] = '';
                                $curCol++;
                            }
                        }

                        $val = isset($cell->v) ? (string) $cell->v : '';
                        $type = (string) $cell['t'];

                        if ($type === 's') {
                            $strIdx = (int) $val;
                            $val = $sharedStrings[$strIdx] ?? '';
                        } elseif ($type === 'inlineStr' && isset($cell->is->t)) {
                            $val = (string) $cell->is->t;
                        }

                        $row[$curCol] = $val;
                        $curCol++;
                    }
                    $rows[] = $row;
                }
            }
        }

        $zip->close();

        return $rows;
    }

    private function parseNisnCsv(string $path): array
    {
        $rows = [];
        $handle = fopen($path, 'r');
        if ($handle === false) {
            return $rows;
        }

        while (($data = fgetcsv($handle, 0, ',')) !== false) {
            if (count($data) === 1 && str_contains((string) $data[0], ';')) {
                $data = str_getcsv($data[0], ';');
            }
            $rows[] = $data;
        }

        fclose($handle);

        return $rows;
    }

    public function toggleRecruitment(Request $request): RedirectResponse
    {
        $this->checkAdmin();

        $status = $request->input('status') === '1' ? '1' : '0';
        Setting::setByKey('recruitment_open', $status);

        $msg = $status === '1' ? 'Pendaftaran Open Recruitment BERHASIL DIBUKA!' : 'Pendaftaran Open Recruitment DITUTUP.';

        return redirect()->back()->with('success', $msg);
    }
}
