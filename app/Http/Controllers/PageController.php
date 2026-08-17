<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\News;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

        // Section "BERITA" (Varian B): kiri = 1 berita unggulan besar, kanan =
        // sidebar 3 buletin terbaru. Dua query terpisah (bukan feed gabungan)
        // agar tiap kolom dijamin berisi tipe konten yang benar.
        $beritaUnggulan = $this->beritaUnggulan();
        $buletinTerbaru = $this->buletinTerbaru(3);

        return view('landing', compact('daftarKomunitas', 'beritaUnggulan', 'buletinTerbaru'));
    }

    /**
     * Berita terpublikasi terbaru untuk kartu besar "Unggulan" (kolom kiri
     * section BERITA di Beranda). Null kalau belum ada berita → view
     * menyembunyikan kolom kiri dan sidebar mengambil lebar penuh.
     *
     * Bentuk array (sama seperti mapping buletin di bawah):
     *   title, excerpt, image (path storage thumbnail | null), date (Carbon),
     *   author, url (route berita.show), target ('_self').
     */
    protected function beritaUnggulan(): ?array
    {
        $n = News::published()
            ->with('user')
            ->orderByDesc('published_at')
            ->first();

        if (! $n) {
            return null;
        }

        return [
            'type' => 'berita',
            'title' => $n->title,
            'excerpt' => $n->excerpt,
            'image' => $n->thumbnail,
            'date' => $n->published_at,
            'author' => $n->user?->name,
            'url' => route('berita.show', $n->slug),
            'target' => '_self',
        ];
    }

    /**
     * N buletin terbaru (Book kategori 'buletin', visible) untuk sidebar
     * "Buletin terbaru" (kolom kanan section BERITA di Beranda).
     * Kembalian selalu Collection (bisa kosong → view merender empty state).
     *
     * URL: PDF langsung (dibuka tab baru) atau fallback ke tab buletin /info.
     */
    protected function buletinTerbaru(int $limit = 3): \Illuminate\Support\Collection
    {
        return Book::visible()
            ->where('category', 'buletin')
            ->latest()
            ->limit($limit)
            ->get()
            ->map(function (Book $b) {
                $pdf = $b->pdf_path ? asset('storage/' . $b->pdf_path) : route('info', ['tab' => 'buletin']);
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

        // Feed selalu Terbaru (latest-first). Toggle Terbaru/Terpopuler sudah
        // dihapus dari UI; sort tidak lagi diturunkan dari ?sort=.
        $query = Post::with($with)->withCount(['comments'])->latest();

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
        $recentPosts = Post::with(['media' => function ($q) { $q->orderBy('order')->limit(1); }])
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
        ]);
    }

    public function komunitasShow(string $slug)
    {
        return $this->komunitasIndex($slug);
    }

    /**
     * Halaman Credits (tim pembuat situs). UNLISTED — tidak ada di navbar/menu;
     * hanya dicapai via logo Liivo di footer. Publik (tanpa login).
     *
     * $tim: tim pembuat. Tiap baris =
     *   ['nama', 'peran', 'accent', 'tagline', 'socials', 'avatar'].
     *  - nama/peran/tagline/accent wajib. accent = HEX untuk cincin/avatar/tag kartu.
     *  - tagline (string HTML): boleh memuat satu kata <em>…</em> yang diberi
     *    warna accent di view.
     *  - socials: [['icon'=>'fa-brands …','url'=>'…','label'=>'…'], …] (boleh kosong).
     *  - avatar: path relatif public/ (mis. 'assets/team/fulan-rahman.jpg').
     *    Swap placeholder → foto asli = UBAH path ini (atau drop file dgn nama sama).
     *    Bila file BELUM ada di disk → view merender ikon orang di lingkaran accent
     *    (placeholder jelas "foto belum diunggah"), bukan gambar pecah.
     *
     * Route: GET /credits
     */
    public function credits()
    {
        $tim = [
            // ── Tim inti (4) ──
            [
                'nama'    => 'Galang Putra Bayu Pratama',
                'peran'   => 'Lead Developer',
                'accent'  => '#C9A66B',
                'tagline' => 'Menjaga arah tim dan memastikan semua bagian saling terhubung dengan baik.',
                'socials' => [
                    ['icon' => 'fa-brands fa-github',    'url' => 'https://github.com/pejalan214', 'label' => 'GitHub'],
                    ['icon' => 'fa-brands fa-instagram', 'url' => 'https://www.instagram.com/11putrabayu1?igsh=MTUxOXJ4ZmpjNTRjcw==&igsi=MTUxOXJ4ZmpjNTRjcw==', 'label' => 'Instagram'],
                ],
                'avatar'  => 'assets/team/galang.jpg',
            ],
            [
                'nama'    => 'Bryan Zidhan Kirana',
                'peran'   => 'Frontend Developer',
                'accent'  => '#34C9A0',
                'tagline' => 'Menerjemahkan desain jadi antarmuka yang rapi dan enak dipakai.',
                'socials' => [
                    ['icon' => 'fa-brands fa-github',    'url' => 'https://github.com/wriothesley-XD', 'label' => 'GitHub'],
                    ['icon' => 'fa-brands fa-instagram', 'url' => 'https://www.instagram.com/bry.luv_g?igsh=MW1meGRoa3Fta2RmaQ==&igsi=MW1meGRoa3Fta2RmaQ==', 'label' => 'Instagram'],
                ],
                'avatar'  => 'assets/team/bren.jpg',
            ],
            [
                'nama'    => 'Khairunnisa Zahira',
                'peran'   => 'Frontend Developer',
                'accent'  => '#5BAFC4',
                'tagline' => 'Fokus pada detail tampilan, dari layout sampai hal-hal kecil yang sering luput dilihat.',
                'socials' => [
                    ['icon' => 'fa-brands fa-github',    'url' => 'https://github.com/khaiz-F', 'label' => 'GitHub'],
                    ['icon' => 'fa-brands fa-instagram', 'url' => 'https://www.instagram.com/mrs.zahira?igsh=anBqaXR5OW0xeDlu&igsi=anBqaXR5OW0xeDlu', 'label' => 'Instagram'],
                ],
                'avatar'  => 'assets/team/kesa.jpg',
            ],
            [
                'nama'    => 'Rifki Abdillah Muis',
                'peran'   => 'Backend Developer',
                'accent'  => '#C9904E',
                'tagline' => 'Membangun fondasi sistem yang bekerja diam-diam di balik layar.',
                'socials' => [
                    ['icon' => 'fa-brands fa-github',    'url' => 'https://github.com/rifkiabdillahmuis-sawit-enjoyer', 'label' => 'GitHub'],
                    ['icon' => 'fa-brands fa-instagram', 'url' => 'https://www.instagram.com/rifki_abdillah_muis?igsh=c2pwMDN4bGhjbzc0', 'label' => 'Instagram'],
                ],
                'avatar'  => 'assets/team/muis.jpg',
            ],
            // ── Anggota baru (3) — nama/foto masih placeholder; peran/tagline final. ──
            [
                'nama'    => 'Dytha Aisha Qamara',
                'peran'   => 'Digital Artist',
                'accent'  => '#E07A9B',
                'tagline' => 'Menerjemahkan konsep visual menjadi karya yang punya karakter.',
                'socials' => [
                    ['icon' => 'fa-brands fa-instagram', 'url' => 'https://www.instagram.com/aisha_qamara?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw==&igsi=ZDNlZDc0MzIxNw==', 'label' => 'Instagram'],
                ],
                'avatar'  => 'assets/team/dhyta.jpg',
            ],
            [
                'nama'    => 'Heykal Fadhila Mudzaki',
                'peran'   => 'Digital Artist',
                'accent'  => '#9B7EBD',
                'tagline' => 'Mengeksekusi ide visual dengan perhatian pada detail dan komposisi.',
                'socials' => [
                    ['icon' => 'fa-brands fa-instagram', 'url' => 'https://www.instagram.com/kellz.fm/?utm_source=ig_web_button_share_sheet', 'label' => 'Instagram'],
                ],
                'avatar'  => 'assets/team/heykal.jpg',
            ],
            [
                'nama'    => 'Bintang Fachria Luckyano',
                'peran'   => 'Content Manager',
                'accent'  => '#D9A441',
                'tagline' => 'Menyusun narasi dan memastikan setiap konten tersampaikan dengan jelas.',
                'socials' => [
                    ['icon' => 'fa-brands fa-instagram', 'url' => 'https://www.instagram.com/arthfiscl?igsh=azRqb3Bnbjg2dHNo&igsi=azRqb3Bnbjg2dHNo', 'label' => 'Instagram'],
                ],
                'avatar'  => 'assets/team/bintang.jpg',
            ],
        ];

        // Prakomputasi per anggota agar view bebas logika PHP/closure.
        //  - tag:       label pill — kata pertama 'peran', upper-case (LEAD/FRONTEND/…/MEMBER).
        //  - initials:  huruf depan 2 kata pertama nama.
        //  - has_img:   TRUE hanya bila 'avatar' terisi DAN file benar-benar ada di disk
        //               (public_path()). Kalau belum di-upload → placeholder ikon orang.
        //  - avatar_url: URL foto asli (asset()) bila has_img; null bila belum ada.
        $tim = collect($tim)->map(function (array $m) {
            // tag = kata pertama peran, upper-case.
            $peranKata = array_values(array_filter(explode(' ', trim($m['peran'] ?? ''))));
            $m['tag'] = mb_strtoupper($peranKata[0] ?? 'TEAM');

            $kata = array_values(array_filter(explode(' ', trim($m['nama'] ?? ''))));
            $initials = '';
            for ($i = 0, $n = min(2, count($kata)); $i < $n; $i++) {
                $initials .= mb_strtoupper(mb_substr($kata[$i], 0, 1));
            }
            $m['initials'] = $initials !== '' ? $initials : '?';

            // Foto asli hanya bila path terisi & file ada di disk.
            $m['has_img'] = ! empty($m['avatar']) && file_exists(public_path($m['avatar']));
            $m['avatar_url'] = $m['has_img'] ? asset($m['avatar']) : null;

            return $m;
        })->all();

        return view('credits', compact('tim'));
    }
}
