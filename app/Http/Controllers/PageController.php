<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\News;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class PageController extends Controller
{
    /**
     * Landing page (Publik — tamu & anggota). Menyediakan preview komunitas.
     * Route: GET /
     */
    public function landing()
    {
        // Data komunitas untuk preview publik di landing (tamu bisa lihat, tanpa auth).
        $daftarKomunitas = Config::get('komunitas.daftar', []);

        // Counter hero — angka asli dari DB (bukan placeholder 0).
        $totalModul = Book::visible()->where('category', 'modul')->count();
        $totalAnggota = User::count();
        $totalKomunitas = count($daftarKomunitas);

        // Section "KABAR TERBARU" (2 blok asimetris): berita untuk rotator Blok A,
        // buletin untuk list ringkas Blok B — dipisah, tidak lagi di-merge.
        $beritaTerbaru = $this->beritaGrid();
        $buletinTerbaru = $this->buletinTerbaru(3);

        // Section "Perpustakaan Digital": 6 koleksi terbaru lintas kategori.
        $katalogPerpus = Book::visible()
            ->latest()
            ->limit(6)
            ->get()
            ->map(fn (Book $b) => [
                'title' => $b->title,
                'category' => $b->category,
                'author' => $b->author,
                'image' => $b->cover_image,
                'pdf' => $b->pdf_path ? asset('storage/'.$b->pdf_path) : null,
            ]);

        return view('landing', compact(
            'daftarKomunitas', 'totalModul', 'totalAnggota', 'totalKomunitas',
            'beritaTerbaru', 'buletinTerbaru', 'katalogPerpus'
        ));
    }

    /**
     * 3 berita terpublikasi terbaru untuk grid "Kabar Terbaru" di Beranda
     * (bentuk array sama dengan buletinTerbaru → bisa di-merge & diurut bareng).
     */
    protected function beritaGrid(): Collection
    {
        return News::published()
            ->with('user')
            ->orderByDesc('published_at')
            ->limit(3)
            ->get()
            ->map(fn (News $n) => [
                'type' => 'berita',
                'title' => $n->title,
                'excerpt' => $n->excerpt,
                'image' => $n->thumbnail,
                'date' => $n->published_at,
                'author' => $n->user?->name,
                'url' => route('berita.show', $n->slug),
                'target' => '_self',
            ]);
    }

    /**
     * N buletin terbaru (Book kategori 'buletin', visible) untuk grid
     * "Kabar Terbaru" di Beranda. Kembalian selalu Collection.
     *
     * URL: PDF langsung (dibuka tab baru) atau fallback ke tab buletin /info.
     */
    protected function buletinTerbaru(int $limit = 3): Collection
    {
        return Book::visible()
            ->where('category', 'buletin')
            ->latest()
            ->limit($limit)
            ->get()
            ->map(function (Book $b) {
                $pdf = $b->pdf_path ? asset('storage/'.$b->pdf_path) : route('info', ['tab' => 'buletin']);

                return [
                    'type' => 'buletin',
                    'title' => $b->title,
                    'excerpt' => $b->description,
                    'image' => $b->cover_image,
                    'date' => $b->created_at,
                    'author' => $b->author,
                    'url' => $pdf,
                    'target' => $b->pdf_path ? '_blank' : '_self',
                ];
            });
    }

    /**
     * Halaman Hub Masjid (Laboratorium PAI & Open Recruitment — Publik).
     * Route: GET /hub
     */
    public function hub()
    {
        return view('landing.hub');
    }

    /**
     * Halaman Pemilihan Role Karakter Komunitas (Slider Carousel).
     * Route: GET /select-role
     */
    public function selectRole()
    {
        $daftarKomunitas = Config::get('komunitas.daftar', []);

        return view('select-role', compact('daftarKomunitas'));
    }

    /**
     * Simpan minat komunitas (selected_community) ke database user secara permanen.
     * Route: POST /select-role
     */
    public function storeRole(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'community_slug' => ['required', 'string', 'max:100'],
        ]);

        $user = Auth::user();
        if ($user) {
            $user->update([
                'selected_community' => $validated['community_slug'],
            ]);
        }

        // Setelah simpan role -> LANGSUNG MASUK KE HALAMAN KOMUNITAS
        return redirect()->route('komunitas');
    }

    /**
     * Direct Beranda ke Halaman Komunitas Feed
     * Route: GET /beranda
     */
    public function beranda()
    {
        return redirect()->route('komunitas');
    }

    /**
     * Halaman Utama Komunitas (Social Media Feed Timeline + FAB + Filter Komunitas).
     * Route: GET /komunitas/{slug?}
     */
    public function komunitasIndex(?string $slug = null)
    {
        $daftarKomunitas = Config::get('komunitas.daftar', []);
        $user = Auth::user();

        // Jika slug tidak diberikan dan user punya selected_community di DB, gunakan itu
        if (! $slug && $user && $user->selected_community) {
            $currentSlug = $user->selected_community;
        } else {
            $currentSlug = $slug ?? 'semua';
        }

        // Eager-load suara user saat ini (0/1 baris) + status simpan (pivot
        // post_user) agar tombol vote/bookmark bisa ditandai aktif sesuai
        // pilihan user. Tamu (guest) tidak dimuat.
        $with = ['user', 'media'];
        if ($user) {
            $with['votes'] = fn ($q) => $q->where('user_id', $user->id)->select(['post_id', 'type']);
            $with['savedBy'] = fn ($q) => $q->where('user_id', $user->id)->select(['post_id']);
        }

        // Sort: terbaru (default) atau terpopuler (berdasarkan jumlah upvotes / likes).
        $sort = request('sort', 'recent');
        $query = Post::with($with)->withCount(['comments']);
        if ($sort === 'popular' || $sort === 'terpopuler') {
            $query->orderByDesc('upvotes')->latest();
        } else {
            $query->latest();
        }

        if ($currentSlug && $currentSlug !== 'semua') {
            $query->where('community_slug', $currentSlug);
        }

        // Pencarian opsional (?q=...) — cari di judul + isi postingan. Tanpa ?q,
        // feed berperilaku seperti sedia kala. withQueryString agar ?q= terbawa
        // antar-halaman pagination.
        $q = trim((string) request('q'));
        if ($q !== '') {
            $query->where(function ($qq) use ($q) {
                $qq->where('title', 'like', "%{$q}%")
                    ->orWhere('content', 'like', "%{$q}%");
            });
        }

        // paginate(10) -> feed timeline single-column; ?page=N shareable.
        // Slug komunitas ada di path (bukan query) jadi tetap terjaga antar-halaman.
        $posts = $query->paginate(10)->withQueryString();
        $komunitasAktif = $currentSlug !== 'semua' ? collect($daftarKomunitas)->firstWhere('slug', $currentSlug) : null;

        // Sidebar kiri: jumlah post per komunitas (DB, bukan hardcode). Satu query
        // grouped lalu dipetakan ke daftarKomunitas (sumber kanonik nama/slug/ikon
        // dari config). Komunitas tanpa post tetap tampil (count 0).
        $counts = Post::query()
            ->select('community_slug', DB::raw('count(*) as total'))
            ->whereIn('community_slug', collect($daftarKomunitas)->pluck('slug'))
            ->groupBy('community_slug')
            ->pluck('total', 'community_slug');

        $komunitasSidebar = collect($daftarKomunitas)->map(function (array $k) use ($counts) {
            return array_merge($k, ['total' => (int) ($counts[$k['slug']] ?? 0)]);
        });
        $totalSemua = $counts->sum();

        // Sidebar kanan: 5 postingan terbaru lintas komunitas (site-wide), untuk
        // panel "Postingan Terbaru". Eager-load media (thumbnail) + comments_count.
        $recentPosts = Post::with(['media' => function ($q) {
            $q->orderBy('order')->limit(1);
        }])
            ->withCount(['comments'])
            ->latest()
            ->limit(5)
            ->get();

        return view('komunitas.index', [
            'daftarKomunitas' => $daftarKomunitas,
            'komunitasSidebar' => $komunitasSidebar,
            'totalSemua' => $totalSemua,
            'recentPosts' => $recentPosts,
            'komunitasAktif' => $komunitasAktif,
            'currentSlug' => $currentSlug,
            'posts' => $posts,
            'sort' => $sort,
        ]);
    }

    public function komunitasShow(string $slug)
    {
        return $this->komunitasIndex($slug);
    }
}
