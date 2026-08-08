<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class LibraryController extends Controller
{
    /**
     * Perpustakaan Digital Publik.
     * Route: GET /perpustakaan
     *
     * Buku difilter server-side via query string (?category=, ?q=) lalu
     * dipaginasi 12 per halaman (grid 4 kolom x 3 baris). Filter dipertahankan
     * antar-halaman memakai withQueryString() -> URL shareable & bookmarkable,
     * mendukung back/forward browser.
     */
    public function index(Request $request)
    {
        $category = $request->input('category', 'semua');
        $q = trim((string) $request->input('q', ''));

        $books = Book::query()
            ->where('is_visible', true)
            ->when($category && $category !== 'semua', function ($query) use ($category) {
                $query->where('category', $category);
            })
            ->when($q !== '', function ($query) use ($q) {
                // Pencarian pada judul ATAU penulis (dikelompokkan agar tidak
                // bocor ke luar scope filter kategori).
                $query->where(function ($query) use ($q) {
                    $query->where('title', 'like', "%{$q}%")
                          ->orWhere('author', 'like', "%{$q}%");
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('perpustakaan', [
            'books' => $books,
            'category' => $category,
            'q' => $q,
        ]);
    }
}
