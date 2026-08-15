<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\News;
use App\Models\Post;
use App\Models\Registration;
use App\Models\Report;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

        if ($user->role !== 'admin') {
            abort(403, 'Akses Ditolak. Halaman ini hanya dapat diakses oleh Admin.');
        }
    }

    /**
     * Dashboard Admin Panel Sederhana.
     * Route: GET /admin-panel
     */
    public function index()
    {
        $this->checkAdmin();

        $perPage = 10;

        // Semua list dimuat sebagai paginator (per halaman) — BUKAN ->get() lagi.
        // Tab hanya merender halaman ke-1; halaman berikutnya di-fetch via AJAX
        // (AdminController::list) saat user pindah halaman atau pakai "View All".
        $users         = User::latest()->paginate($perPage);
        $books         = Book::latest()->paginate($perPage);
        $posts         = Post::with('user')->latest()->paginate($perPage);
        $registrations = Registration::latest()->paginate($perPage);
        $news          = News::with('user')->latest()->paginate($perPage);
        $isRecruitmentOpen = Setting::getByKey('recruitment_open', '1') === '1';

        // Laporan konten pending (untuk badge + Perlu Perhatian + tab Laporan).
        $laporan = Report::pending()->with(['reportable.user', 'reporter'])->latest()->paginate($perPage);
        $pendingReportCount = $laporan->total();

        // Feed "Perlu Perhatian" di dashboard: 5 laporan terbaru dari halaman aktif.
        $perluPerhatian = $laporan->getCollection()->take(5);

        $stats = [
            'total_users' => $users->total(),
            'total_posts' => $posts->total(),
            'total_books' => $books->total(),
            'total_registrations' => $registrations->total(),
            'total_news' => $news->total(),
        ];

        return view('admin.index', compact('users', 'books', 'posts', 'registrations', 'news', 'isRecruitmentOpen', 'stats', 'laporan', 'pendingReportCount', 'perluPerhatian'));
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
            'users'         => ['view' => 'admin._list_users',         'query' => User::latest()],
            'books'         => ['view' => 'admin._list_books',         'query' => Book::latest(), 'search' => ['title', 'author', 'category']],
            'news'          => ['view' => 'admin._list_news',          'query' => News::with('user')->latest()],
            'posts'         => ['view' => 'admin._list_posts',         'query' => Post::with('user')->latest(),         'itemView' => 'admin._post_item',   'itemVar' => 'post', 'groupField' => 'community_slug'],
            'registrations' => ['view' => 'admin._list_registrations', 'query' => Registration::latest()],
            'laporan'       => ['view' => 'admin._list_laporan',       'query' => Report::pending()->with(['reportable.user', 'reporter'])->latest(), 'itemView' => 'admin._laporan_item', 'itemVar' => 'r', 'groupField' => 'reportable_type'],
        ];

        if (! isset($map[$resource])) {
            abort(404);
        }
        $cfg = $map[$resource];

        // Filter pencarian teks (?q=) — hanya resource yang mendeklarasikan 'search';
        // dicocokkan via LIKE pada salah satu field (OR). Lihat [data-admin-search]
        // di resources/views/admin/_tab_books.blade.php.
        $query  = $cfg['query'];
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
                    'html'  => view($cfg['itemView'], [$cfg['itemVar'] => $model])->render(),
                ];
            })->values();
        }

        return response()->json([
            'html'        => $html,
            'items'       => $items,
            'currentPage' => $paginator->currentPage(),
            'lastPage'    => $paginator->lastPage(),
            'total'       => $paginator->total(),
            'perPage'     => $paginator->perPage(),
            'from'        => $paginator->firstItem(),
            'to'          => $paginator->lastItem(),
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
            'title'        => ['required', 'string', 'max:255'],
            'slug'         => ['nullable', 'string', 'max:255'],
            'excerpt'      => ['nullable', 'string', 'max:500'],
            'content'      => ['required', 'string'],
            'thumbnail'    => ['nullable', 'image', 'max:4096'],
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
     * Buka/Tutup Sakelar Open Recruitment.
     * Route: POST /admin-panel/toggle-recruitment
     */
    public function toggleRecruitment(Request $request): RedirectResponse
    {
        $this->checkAdmin();

        $status = $request->input('status') === '1' ? '1' : '0';
        Setting::setByKey('recruitment_open', $status);

        $msg = $status === '1' ? 'Pendaftaran Open Recruitment BERHASIL DIBUKA!' : 'Pendaftaran Open Recruitment DITUTUP.';

        return redirect()->back()->with('success', $msg);
    }
}
