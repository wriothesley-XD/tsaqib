<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LibraryController extends Controller
{
    /**
     * Label tampilan untuk kategori (genre) yang dikenal. Kategori yang belum
     * terdaftar tetap muncul otomatis dari DB — di-title-case sebagai fallback.
     */
    private const GENRE_LABELS = [
        'fiqih' => 'Fiqih',
        'aqidah' => 'Aqidah',
        'ski' => 'SKI',
        'hadits' => 'Hadits & Tafsir',
        'modul' => 'Modul PAI',
    ];

    /**
     * Perpustakaan Digital — satu route publik, banyak mode via query string:
     *
     *   ?view=collection   -> grid "My Collection" milik user (wajib login)
     *   ?view=saved        -> grid "Saved" milik user (wajib login)
     *   ?category=<key>    -> grid full satu genre (tujuan tombol "View All")
     *   ?q=...             -> hasil pencarian global (atau dalam scope aktif)
     *   (default)          -> carousel horizontal per-genre
     *
     * Route, navbar, dan middleware tidak diubah — hanya query param yang
     * menentukan mode render.
     */
    public function index(Request $request)
    {
        $q = trim((string) $request->input('q', ''));
        $view = (string) $request->input('view', '');
        $category = (string) $request->input('category', 'semua');

        // 1) Daftar pribadi (auth only). Guest diredirect ke login + intended URL.
        if (in_array($view, ['collection', 'saved'], true)) {
            if (! $request->user()) {
                return redirect()->guest(route('login'));
            }

            return $this->forUserList($request, $view, $q);
        }

        // 2) Single-genre grid ("View All"). Search di sini tetap dalam genre.
        if ($category !== 'semua' && $category !== '') {
            return $this->forGenreGrid($request, $category, $q);
        }

        // 3) Pencarian global (dari mode default / semua genre).
        if ($q !== '') {
            return $this->forSearch($request, $q);
        }

        // 4) Default: carousel per-genre.
        return $this->forRows($request);
    }

    /**
     * Mode default — satu carousel horizontal per genre + sidebar genre nav.
     */
    protected function forRows(Request $request)
    {
        $genreNav = $this->genreNav();

        // Lampirkan sampel maks. 7 buku terbaru per genre untuk carousel-nya.
        $genres = $genreNav->map(function (array $g) {
            $g['books'] = Book::visible()
                ->where('category', $g['key'])
                ->latest()
                ->limit(7)
                ->get();

            return $g;
        });

        return view('perpustakaan', $this->baseView('rows', [
            'genres' => $genres,
            'genreNav' => $genreNav,
        ], $request));
    }

    /**
     * Grid full satu genre — paginated, dengan opsional pencarian dalam genre.
     */
    protected function forGenreGrid(Request $request, string $category, string $q)
    {
        $books = Book::visible()
            ->where('category', $category)
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($query) use ($q) {
                    $query->where('title', 'like', "%{$q}%")
                        ->orWhere('author', 'like', "%{$q}%");
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('perpustakaan', $this->baseView('genre', [
            'books' => $books,
            'genreKey' => $category,
            'genreLabel' => $this->genreLabel($category),
            'genreNav' => $this->genreNav(),
            'q' => $q,
        ], $request));
    }

    /**
     * Hasil pencarian global (semua genre) — paginated grid.
     */
    protected function forSearch(Request $request, string $q)
    {
        $books = Book::visible()
            ->where(function ($query) use ($q) {
                $query->where('title', 'like', "%{$q}%")
                    ->orWhere('author', 'like', "%{$q}%");
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('perpustakaan', $this->baseView('search', [
            'books' => $books,
            'genreNav' => $this->genreNav(),
            'q' => $q,
        ], $request));
    }

    /**
     * Grid buku pribadi user (collection | saved) — paginated, scoped + search.
     */
    protected function forUserList(Request $request, string $view, string $q)
    {
        $books = $request->user()
            ->savedBooks()
            ->wherePivot('type', $view)
            ->where('books.is_visible', true)
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($query) use ($q) {
                    $query->where('title', 'like', "%{$q}%")
                        ->orWhere('author', 'like', "%{$q}%");
                });
            })
            ->orderByPivot('created_at', 'desc')
            ->paginate(12)
            ->withQueryString();

        return view('perpustakaan', $this->baseView($view, [
            'books' => $books,
            'genreNav' => $this->genreNav(),
            'listType' => $view,
            'q' => $q,
        ], $request));
    }

    /**
     * Toggle keanggotaan buku pada daftar pribadi (collection | saved).
     * Route: POST /perpustakaan/books/{book}/toggle (auth) — return JSON.
     */
    public function toggleSave(Request $request, Book $book)
    {
        $data = $request->validate([
            'type' => ['required', 'in:collection,saved'],
        ]);

        $match = [
            'user_id' => $request->user()->id,
            'book_id' => $book->id,
            'type' => $data['type'],
        ];

        // Pivot dimanipulasi langsung agar unambiguous untuk kolom `type`.
        if (DB::table('book_user')->where($match)->exists()) {
            DB::table('book_user')->where($match)->delete();
            $active = false;
        } else {
            DB::table('book_user')->insert(array_merge($match, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
            $active = true;
        }

        return response()->json([
            'active' => $active,
            'type' => $data['type'],
        ]);
    }

    // ------------------------------------------------------------------
    // Helpers
    // ------------------------------------------------------------------

    /**
     * Daftar genre dari DB (real): key, slug (anchor), label, jumlah buku.
     * Dipakai sidebar di semua mode. Tidak mengambil sampel buku di sini.
     */
    protected function genreNav()
    {
        return Book::visible()
            ->select('category', DB::raw('count(*) as total'))
            ->groupBy('category')
            ->orderByDesc('total')
            ->get()
            ->map(function ($row) {
                return [
                    'key' => $row->category,
                    'slug' => Str::slug($row->category),
                    'label' => $this->genreLabel($row->category),
                    'total' => (int) $row->total,
                ];
            });
    }

    /**
     * Label tampilan genre: peta dikenal, atau title-case fallback.
     */
    protected function genreLabel(string $category): string
    {
        return self::GENRE_LABELS[$category]
            ?? Str::title(str_replace(['_', '-'], ' ', $category));
    }

    /**
     * Konsolidasi variabel yang selalu dibutuhkan view: mode aktif, genre nav,
     * dan ID buku milik user (untuk state awal tombol bookmark).
     */
    protected function baseView(string $mode, array $extra, Request $request): array
    {
        $user = $request->user();

        // Cast ke int: pluck() bisa mengembalikan id sebagai string tergantung
        // driver PDO, sementara in_array() di komponen book-card memakai strict.
        $collectionIds = $user
            ? $user->savedBooks()->wherePivot('type', 'collection')->pluck('books.id')->map(fn ($id) => (int) $id)->all()
            : [];

        $savedIds = $user
            ? $user->savedBooks()->wherePivot('type', 'saved')->pluck('books.id')->map(fn ($id) => (int) $id)->all()
            : [];

        return array_merge([
            'mode' => $mode,
            'genreNav' => collect(),
            'genres' => collect(),
            'collectionIds' => $collectionIds,
            'savedIds' => $savedIds,
        ], $extra);
    }
}
