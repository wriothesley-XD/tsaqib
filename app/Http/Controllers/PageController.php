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

        $query = Post::with('user')->latest();

        if ($currentSlug && $currentSlug !== 'semua') {
            $query->where('community_slug', $currentSlug);
        }

        // paginate(10) -> feed timeline single-column; ?page=N shareable.
        // Slug komunitas ada di path (bukan query) jadi tetap terjaga antar-halaman.
        $posts = $query->paginate(10);
        $komunitasAktif = $currentSlug !== 'semua' ? collect($daftarKomunitas)->firstWhere('slug', $currentSlug) : null;

        return view('komunitas.index', [
            'daftarKomunitas' => $daftarKomunitas,
            'komunitasAktif' => $komunitasAktif,
            'currentSlug' => $currentSlug,
            'posts' => $posts,
        ]);
    }

    public function komunitasShow(string $slug)
    {
        return $this->komunitasIndex($slug);
    }
}
