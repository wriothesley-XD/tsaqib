<?php

namespace App\Http\Controllers;

use App\Models\ActivityDocumentation;
use App\Models\Book;
use App\Models\News;

class NewsController extends Controller
{
    /**
     * Pusat Informasi (/info) — Berita + Buletin dalam satu halaman 2 tab.
     * Tab switching dilakukan client-side (tanpa reload). ?tab= atau #hash
     * menentukan tab awal agar link tetap bisa dibagikan.
     *
     *   Tab "Berita"  → berita terpublikasi (News::published()).
     *   Tab "Buletin" → buku Perpustakaan berkategori 'buletin' (edisi PDF).
     *   Tab "Dokumentasi" → galeri kegiatan (ActivityDocumentation).
     *
     * Route: GET /info
     */
    public function info()
    {
        $news = News::published()
            ->with('user')
            ->orderByDesc('published_at')
            ->get();

        $buletin = Book::visible()
            ->where('category', 'buletin')
            ->latest()
            ->get();

        $documentations = ActivityDocumentation::with('photos')
            ->orderByDesc('event_date')
            ->orderByDesc('created_at')
            ->get();

        $initialTab = in_array(request('tab'), ['berita', 'buletin', 'dokumentasi'], true)
            ? request('tab')
            : 'berita';

        return view('info', compact('news', 'buletin', 'documentations', 'initialTab'));
    }

    /**
     * Detail satu dokumentasi kegiatan + galeri foto (dengan lightbox).
     * Route: GET /info/dokumentasi/{slug}
     */
    public function showDocumentation(string $slug)
    {
        $doc = ActivityDocumentation::with('photos')
            ->where('slug', $slug)
            ->firstOrFail();

        return view('dokumentasi.show', compact('doc'));
    }

    /**
     * Detail satu berita berdasarkan slug. Hanya yang sudah terbit.
     * Route: GET /berita/{slug}
     */
    public function show(string $slug)
    {
        $news = News::published()
            ->with('user')
            ->where('slug', $slug)
            ->firstOrFail();

        $recent = News::published()
            ->where('id', '!=', $news->id)
            ->orderByDesc('published_at')
            ->limit(5)
            ->get();

        // Isi berita adalah plain text dari textarea → pecah per baris kosong
        // menjadi paragraf agar tampil rapi di view (logic di controller, bukan blade).
        $paragraphs = collect(preg_split('/\n\s*\n/', trim((string) $news->content)))
            ->map(fn ($p) => trim($p))
            ->filter()
            ->values();

        return view('berita.show', compact('news', 'recent', 'paragraphs'));
    }
}
