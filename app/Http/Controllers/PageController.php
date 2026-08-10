<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

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

        return view('landing', compact('daftarKomunitas'));
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

        // Tab sort: Terbaru (default, latest-first) atau Terpopuler (net votes).
        $sort = request('sort') === 'popular' ? 'popular' : 'recent';

        // Eager-load suara + repost user saat ini (0/1 baris) agar tombol bisa
        // ditandai aktif sesuai pilihan user. Tamu (guest) tidak dimuat.
        $with = ['user', 'media'];
        if ($user) {
            $with['votes'] = fn ($q) => $q->where('user_id', $user->id)->select(['post_id', 'type']);
            $with['reposts'] = fn ($q) => $q->where('user_id', $user->id)->select(['post_id']);
        }

        $query = Post::with($with)->withCount(['comments', 'reposts']);
        if ($sort === 'popular') {
            // Terpopuler: selisih upvote-downvote, tiebreak terbaru.
            $query->orderByRaw('(upvotes - downvotes) desc, created_at desc');
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

        return view('komunitas.index', [
            'daftarKomunitas' => $daftarKomunitas,
            'komunitasAktif' => $komunitasAktif,
            'currentSlug' => $currentSlug,
            'sort' => $sort,
            'posts' => $posts,
        ]);
    }

    public function komunitasShow(string $slug)
    {
        return $this->komunitasIndex($slug);
    }
}
