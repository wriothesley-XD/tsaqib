{{-- resources/views/komunitas/index.blade.php --}}
@php($pageTitle = 'Feed Komunitas TSAQIB - SMAN 1 Bukittinggi')

@push('styles')
<style>
    /* Vote buttons — base inactive + state aktif (is-up/is-down).
       JS hanya toggle class aktif; warna ditata disini. */
    .vote-btn{ background:rgba(247,245,239,.05); color:rgba(247,245,239,.6); transition:background .15s ease,color .15s ease; }
    .vote-btn:hover{ background:rgba(247,245,239,.10); color:var(--cream); }
    .vote-btn.is-up{ background:var(--green); color:#fff; }
    .vote-btn.is-up:hover{ background:var(--green-dark); color:#fff; }
    .vote-btn.is-down{ background:rgba(239,68,68,.20); color:#fca5a5; }
    .vote-btn:disabled{ opacity:.6; cursor:default; }

    /* Action chips + save active + toast (komentar/simpan/share) */
    .action-chip{ background:rgba(247,245,239,.05); color:rgba(247,245,239,.6); transition:background .15s ease,color .15s ease; cursor:pointer; }
    .action-chip:hover{ background:rgba(247,245,239,.10); color:var(--cream); }
    .save-btn.is-active{ background:var(--gold); color:#10140F; }
    .save-btn.is-active:hover{ background:var(--green-dark); color:#fff; }
    #toast{ transition:opacity .2s ease; }

    /* FIX mobile drawer (Komunitas-only): backdrop bg-black/50 transparan ->
       footer tembus pandang. Solid bg + panel setinggi viewport.
       Selector ID menang dari utility class Tailwind; hanya berlaku di
       halaman ini karena style di-push dari view Komunitas. */
    #mobile-menu-backdrop{ background-color:#10140F; }
    #mobile-menu{ bottom:0; }

    /* ===== MEDIA GRID (feed) — layout menyesuaikan jumlah ===== */
    .media-grid{ display:grid; gap:.25rem; border-radius:.75rem; overflow:hidden; }
    .media-grid.cols-1{ grid-template-columns:1fr; }
    .media-grid.cols-2{ grid-template-columns:1fr 1fr; }
    .media-grid.cols-3{ grid-template-columns:1fr 1fr; }
    @media(min-width:640px){ .media-grid.cols-3{ grid-template-columns:1fr 1fr 1fr; } }
    .media-grid.cols-4{ grid-template-columns:1fr 1fr; }
    .media-tile{ position:relative; aspect-ratio:1/1; background:rgba(247,245,239,.05); cursor:zoom-in; overflow:hidden; }
    .media-tile img,.media-tile video{ width:100%; height:100%; object-fit:cover; display:block; }
    /* Foto tunggal: tanpa box 16:10 paksa & tanpa latar blur — tinggi mengikuti
       rasio asli foto (max-height wajar), foto contain + center horizontal. */
    .media-tile.single{ aspect-ratio:auto; max-height:32rem; display:flex; align-items:center; justify-content:center; background:rgba(16,20,15,.4); }
    .media-tile.single > img{ width:auto; height:auto; max-width:100%; max-height:32rem; object-fit:contain; margin:0 auto; }
    .media-grid.cols-1 .media-tile > video{ object-fit:contain; object-position:center center; background:#000; }
    /* Overlay "+N": menempel penuh di ATAS tile foto terakhir (.media-tile sudah
       position:relative). inset:0 = tutup seluruh foto; 55% gelap -> foto tetap
       samar terlihat; teks center horizontal+vertikal; z-index di atas img. */
    .media-more{ position:absolute; inset:0; z-index:2; display:flex; align-items:center; justify-content:center;
        background:rgba(16,20,15,.55); color:var(--cream); font-weight:800; font-size:1.4rem; }
    .media-play{ position:absolute; inset:0; display:flex; align-items:center; justify-content:center;
        background:rgba(16,20,15,.35); pointer-events:none; }
    .media-play i{ color:#fff; font-size:1.6rem; filter:drop-shadow(0 2px 6px rgba(0,0,0,.6)); }

    /* ===== UPLOADER (modal buat/edit) ===== */
    .media-drop{ border:1px dashed rgba(247,245,239,.2); border-radius:.75rem; padding:.6rem; }
    .media-preview{ display:grid; grid-template-columns:repeat(auto-fill,80px); gap:.4rem; margin-top:.4rem; min-height:84px; }
    .media-preview:empty::before{ content:'Pratinjau foto/video muncul di sini'; grid-column:1/-1; display:flex; align-items:center; justify-content:center; min-height:80px; font-size:10px; color:rgba(247,245,239,.3); }
    .thumb{ position:relative; aspect-ratio:1/1; border-radius:.5rem; overflow:hidden;
        background:rgba(247,245,239,.05); border:1px solid rgba(247,245,239,.1); }
    .thumb img,.thumb video{ width:100%; height:100%; object-fit:cover; }
    .thumb-video{ width:100%; height:100%; display:flex; align-items:center; justify-content:center; color:var(--gold); font-size:1.1rem; }
    .thumb-remove{ position:absolute; top:2px; right:2px; width:20px; height:20px; border-radius:999px;
        background:rgba(239,68,68,.9); color:#fff; font-size:10px; display:flex; align-items:center; justify-content:center; cursor:pointer; }
    .thumb-remove:hover{ background:rgba(239,68,68,1); }
    .thumb-bad{ position:absolute; inset:0; display:flex; align-items:center; justify-content:center; text-align:center;
        background:rgba(127,29,29,.88); color:#fecaca; font-size:9px; font-weight:700; padding:4px; }
    .media-count{ font-size:10px; color:rgba(247,245,239,.5); margin-top:.4rem; }
    .media-warn{ color:#fca5a5; font-weight:600; }
    /* Tombol Terbitkan / Simpan Perubahan saat validasi media memblok submit
       (foto+video campur, >6 foto, >1 video). Pakai CSS biasa di sini agar tidak
       bergantung pada build Tailwind — divalidasi via JS, ditata disini. */
    .btn-submit:disabled{ opacity:.5; cursor:not-allowed; }

    /* ===== LIGHTBOX (galeri: navigasi + transisi + bottom-sheet mobile) ===== */
    #lightbox{ position:fixed; inset:0; z-index:60; background:rgba(0,0,0,.92);
        display:flex; align-items:center; justify-content:center; padding:1rem;
        opacity:0; visibility:hidden; transition:opacity .25s ease, visibility .25s; }
    #lightbox.open{ opacity:1; visibility:visible; }
    #lb-item{ transition:opacity .18s ease, transform .18s ease; }
    #lb-item.fade{ opacity:0; }
    #lb-item img,#lb-item video{ max-width:100%; max-height:80vh; border-radius:.5rem; display:block; box-shadow:0 20px 60px rgba(0,0,0,.6); }
    .lb-stage{ position:relative; max-width:100%; max-height:100%; display:flex; align-items:center; justify-content:center; }
    .lb-btn{ position:absolute; width:42px; height:42px; border-radius:999px;
        background:rgba(247,245,239,.1); color:rgba(247,245,239,.85); display:flex; align-items:center; justify-content:center;
        font-size:1rem; transition:background .15s, color .15s; }
    .lb-btn:hover{ background:rgba(247,245,239,.2); color:var(--gold); }
    .lb-prev{ left:.5rem; top:50%; transform:translateY(-50%); }
    .lb-next{ right:.5rem; top:50%; transform:translateY(-50%); }
    .lb-close{ top:1rem; right:1rem; }
    .lb-counter{ position:absolute; top:1rem; left:50%; transform:translateX(-50%);
        background:rgba(16,20,15,.7); color:var(--cream); font-size:11px; font-weight:700;
        padding:4px 12px; border-radius:999px; }
    @media(max-width:640px){
        #lightbox{ align-items:flex-end; padding:0; }
        .lb-stage{ width:100%; border-radius:1rem 1rem 0 0; background:#000; padding:.5rem .5rem 1.25rem; }
        #lb-item img,#lb-item video{ max-height:72vh; }
        .lb-prev{ left:.75rem; } .lb-next{ right:.75rem; }
    }

    /* ===== SIDEBAR KOMUNITAS (lg+) + CHIP FILTER MOBILE =====
       Nilai diambil dari .genre-link/.genre-pill perpustakaan (didefinisikan lokal
       di view itu, bukan global) agar bahasa visual sidebar seragam antar-halaman. */
    .k-nav-link{ display:flex; align-items:center; gap:.6rem; padding:.5rem .6rem;
        border-radius:.65rem; font-size:.75rem; font-weight:600;
        color:rgba(247,245,239,.65); transition:background .15s ease,color .15s ease; }
    .k-nav-link:hover{ background:rgba(247,245,239,.05); color:var(--cream); }
    .k-nav-link.active{ background:rgba(1,121,95,.2); color:var(--cream); }
    .k-count{ margin-left:auto; font-size:10px; font-weight:700; padding:2px 8px;
        border-radius:999px; background:rgba(247,245,239,.06); color:rgba(247,245,239,.45); }
    .k-nav-link.active .k-count{ background:rgba(201,166,107,.2); color:var(--gold); }

    .k-chip{ display:inline-flex; align-items:center; gap:.4rem; padding:.45rem .8rem;
        border-radius:999px; border:1px solid rgba(247,245,239,.12);
        background:rgba(247,245,239,.05); color:rgba(247,245,239,.7);
        font-size:11px; font-weight:600; transition:background .15s ease,color .15s ease; }
    .k-chip:hover{ background:rgba(247,245,239,.1); color:var(--cream); }
    .k-chip.active{ background:rgba(1,121,95,.28); color:var(--gold); border-color:rgba(201,166,107,.35); }

    /* Menu "..." (Edit/Hapus) — dropdown kecil kanan-atas kartu. */
    .k-menu{ position:relative; }
    .k-menu > summary{ list-style:none; }
    .k-menu > summary::-webkit-details-marker{ display:none; }
    .k-menu-panel{ position:absolute; right:0; top:calc(100% + 4px); min-width:9rem; z-index:30;
        background:#161a14; border:1px solid rgba(247,245,239,.12); border-radius:.65rem;
        padding:4px; box-shadow:0 12px 32px rgba(0,0,0,.5); }
    .k-menu-item{ display:flex; align-items:center; gap:.55rem; width:100%; text-align:left;
        padding:.5rem .6rem; border-radius:.5rem; font-size:12px; font-weight:600; transition:background .15s ease,color .15s ease; }
</style>
@endpush

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    @include('partials.theme-head')
</head>
<body class="text-[var(--cream)] font-sans antialiased min-h-screen flex flex-col relative overflow-x-hidden">

    <!-- Unified TSAQIB Navbar -->
    @include('partials.navbar')

    <main class="flex-1 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10 space-y-6 w-full">

        <!-- Title Banner -->
        <div class="tsaqib-card p-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-display font-bold text-[var(--cream)]">
                        Timeline Feed Komunitas
                        @if($currentSlug !== 'semua')
                            <span class="text-[var(--gold)]">- {{ collect($daftarKomunitas)->firstWhere('slug', $currentSlug)['nama'] ?? '' }}</span>
                        @endif
                    </h1>
                    <p class="text-white/50 text-xs mt-0.5">Kumpulan postingan kegiatan, pengumuman, dan karya 7 komunitas FSI</p>
                </div>
                @auth
                    <button type="button" data-action="open-create" class="hidden sm:inline-flex items-center space-x-2 px-4 py-2.5 rounded-xl bg-[#01795F] hover:bg-[#3F704D] text-white font-semibold text-xs shadow-sm transition">
                        <i class="fa-solid fa-plus"></i>
                        <span>Buat Postingan</span>
                    </button>
                @endauth
            </div>
        </div>

        {{-- Tiga kolom (Reddit-style): sidebar komunitas (lg+) | feed | widget
             Postingan Terbaru (xl+). Kolom ketiga baru muncul di xl agar feed
             tidak terperes di lg. minmax(0,1fr) wajib agar media-grid di kartu
             post tidak melebarkan track kolom. --}}
        <div class="lg:grid lg:grid-cols-[250px_minmax(0,1fr)] xl:grid-cols-[250px_minmax(0,1fr)_270px] lg:gap-6 xl:gap-8">

            {{-- ============ SIDEBAR KIRI: daftar komunitas (lg+) ============ --}}
            <aside class="hidden lg:block">
                <div class="sticky top-24">
                    <nav class="tsaqib-card p-2 space-y-0.5">
                        <p class="px-2 mb-1 text-[10px] font-bold uppercase tracking-wider text-white/40">Komunitas FSI</p>

                        {{-- "Semua" pakai slug eksplisit: /komunitas polos akan
                             me-redirect user login ke selected_community-nya. --}}
                        <a href="{{ route('komunitas', 'semua') }}"
                           class="k-nav-link {{ $currentSlug === 'semua' ? 'active' : '' }}"
                           {{ $currentSlug === 'semua' ? 'aria-current="page"' : '' }}>
                            <span class="w-9 h-9 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-layer-group text-[var(--gold)] text-xs"></i>
                            </span>
                            <span class="min-w-0 flex-1 truncate">Semua</span>
                            <span class="k-count">{{ $totalSemua }}</span>
                        </a>

                        @foreach($komunitasSidebar as $k)
                            <a href="{{ route('komunitas', $k['slug']) }}"
                               class="k-nav-link {{ $currentSlug === $k['slug'] ? 'active' : '' }}"
                               {{ $currentSlug === $k['slug'] ? 'aria-current="page"' : '' }}>
                                <img src="{{ asset($k['image']) }}" alt="" loading="lazy" onerror="this.remove()"
                                     class="w-9 h-9 rounded-lg object-cover shrink-0 bg-white/5">
                                <span class="min-w-0 flex-1 truncate">{{ $k['nama'] }}</span>
                                <span class="k-count">{{ $k['total'] }}</span>
                            </a>
                        @endforeach
                    </nav>
                </div>
            </aside>

            {{-- ============ KOLOM KANAN: feed (search/tabs/postingan) ============ --}}
            <div class="min-w-0 space-y-6">

                {{-- Chip filter mobile (< lg) — pengganti sidebar, baris horizontal
                     yang bisa digeser. -mx bleed agar chip terpotong menandakan
                     masih ada isi (padding main naik ke px-6 di sm). --}}
                <div class="lg:hidden flex gap-2 overflow-x-auto -mx-4 px-4 sm:-mx-6 sm:px-6 pb-1" style="scrollbar-width:none;">
                    <a href="{{ route('komunitas', 'semua') }}"
                       class="k-chip shrink-0 whitespace-nowrap {{ $currentSlug === 'semua' ? 'active' : '' }}">
                        <i class="fa-solid fa-layer-group text-[10px] text-[var(--gold)]"></i> Semua
                        <span class="opacity-60 ml-0.5">{{ $totalSemua }}</span>
                    </a>
                    @foreach($komunitasSidebar as $k)
                        <a href="{{ route('komunitas', $k['slug']) }}"
                           class="k-chip shrink-0 whitespace-nowrap {{ $currentSlug === $k['slug'] ? 'active' : '' }}">
                            <img src="{{ asset($k['image']) }}" alt="" loading="lazy" onerror="this.remove()"
                                 class="w-5 h-5 rounded object-cover">
                            {{ $k['nama'] }} <span class="opacity-60 ml-0.5">{{ $k['total'] }}</span>
                        </a>
                    @endforeach
                </div>

        <!-- Search bar — cari postingan di feed (parameter ?q=, diproses di PageController::komunitasIndex) -->
        <div class="tsaqib-card p-3 sm:p-4">
            <form action="{{ request()->url() }}" method="GET" role="search" class="flex items-center gap-2">
                <div class="relative flex-1">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-white/40 text-xs pointer-events-none"></i>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari postingan..."
                           class="tsaqib-input w-full pl-9 pr-3 py-2.5 text-xs">
                </div>
                @if(request('q'))
                    <a href="{{ request()->url() }}" class="px-3 py-2.5 rounded-xl text-xs font-semibold bg-white/10 text-white/70 hover:text-white whitespace-nowrap">Reset</a>
                @endif
                <button type="submit" class="px-4 py-2.5 rounded-xl bg-[#01795F] hover:bg-[#3F704D] text-white text-xs font-semibold whitespace-nowrap">
                    <i class="fa-solid fa-magnifying-glass sm:hidden"></i>
                    <span class="hidden sm:inline">Cari</span>
                </button>
            </form>
        </div>

        @if(session('success'))
            <div class="p-4 rounded-xl bg-[#01795F]/15 text-[#3fd6b0] border border-[#01795F]/30 text-xs font-semibold">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="p-3 rounded-xl bg-red-500/10 text-red-300 border border-red-500/30 text-xs font-semibold">
                {{ $errors->first() }}
            </div>
        @endif

        {{-- SORT TABS: Terbaru (default) / Terpopuler. ?sort= dipreservasi oleh pagination. --}}
        <div class="tsaqib-card p-1.5 flex items-center gap-1">
            @foreach(['recent' => ['Terbaru', 'fa-clock'], 'popular' => ['Terpopuler', 'fa-fire']] as $sortKey => $tab)
    <a href="{{ request()->fullUrlWithQuery(['sort' => $sortKey, 'page' => 1]) }}"
       class="flex-1 text-center py-2 rounded-lg text-xs font-bold uppercase tracking-wide transition {{ ($sort ?? request('sort', 'recent')) === $sortKey ? 'bg-[#01795F] text-white shadow-sm' : 'text-white/60 hover:text-white' }}">
        <i class="fa-solid {{ $tab[1] }} mr-1.5"></i>{{ $tab[0] }}
    </a>
@endforeach
        </div>

        {{-- POSTS TIMELINE FEED — kartu ala Reddit: kartu terpisah ber-spacing,
             media full-bleed (overflow-hidden), hover bawaan .tsaqib-card. --}}
        <div class="space-y-4 sm:space-y-5">
            @forelse($posts as $post)
                <article class="tsaqib-card overflow-hidden cursor-pointer transition duration-150"
                         data-post-id="{{ $post->id }}"
                         data-post-url="{{ route('komunitas.post.show', $post->id) }}">
                    @include('komunitas._post-card', ['post' => $post, 'showManage' => true, 'compact' => true])

                </article>
            @empty
                <div class="tsaqib-card-flat p-8 text-center text-white/40 text-xs">
                    Belum ada postingan di kategori ini. Tekan tombol (+) untuk menerbitkan postingan pertama!
                </div>
            @endforelse
        </div>

        {{-- Pagination controls (themed) — slug komunitas ada di path, jadi
             pindah halaman tidak mengubah kategori. ?page=N shareable. --}}
        @if ($posts->hasPages())
            <div class="pt-2">
                {{ $posts->links('partials.pagination') }}
            </div>
        @endif

            </div>{{-- /kolum tengah: feed --}}

            {{-- ============ KOLOM KANAN (xl+): widget Postingan Terbaru ============
                 Komponen sama, cuma pindah kolom. Hidden < xl (feed butuh ruang);
                 konten/logic widget tidak berubah. --}}
            <aside class="hidden xl:block">
                <div class="sticky top-24">
                    @include('komunitas._recent-posts')
                </div>
            </aside>
        </div>{{-- /grid tiga kolom --}}

    </main>

    <!-- FLOATING ACTION BUTTON (+) -->
    @auth
        <div class="fixed bottom-6 right-6 z-40">
            <button type="button" data-action="open-create"
                    class="w-14 h-14 rounded-full bg-[#01795F] hover:bg-[#3F704D] text-white shadow-xl flex items-center justify-center text-2xl font-bold transition-all transform hover:scale-110 focus:outline-none"
                    title="Buat Postingan Baru">
                <i class="fa-solid fa-plus"></i>
            </button>
        </div>

        <!-- CREATE POST MODAL -->
        <div id="create-post-modal" class="hidden fixed inset-0 z-[80] flex items-center justify-center p-4" style="background:rgba(13,51,39,0.55); -webkit-backdrop-filter:blur(8px); backdrop-filter:blur(8px);">
            <div class="bg-[#161a14] border border-white/10 rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-4 max-h-[90vh] overflow-y-auto relative">
                <div class="flex items-center justify-between pb-3 border-b border-white/10">
                    <h3 class="font-display font-bold text-[var(--cream)] text-base flex items-center space-x-2">
                        <i class="fa-solid fa-pen-to-square text-[var(--gold)]"></i>
                        <span>Buat Postingan Baru</span>
                    </h3>
                    <button type="button" data-close-create class="text-white/40 hover:text-white">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-white/80 uppercase mb-1">Judul Postingan</label>
                        <input type="text" name="title" required placeholder="Contoh: Dokumen Kegiatan Tahfidz..."
                               class="tsaqib-input w-full px-4 py-2.5 text-xs">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-white/80 uppercase mb-1">Kategori Komunitas</label>
                        <select name="community_slug" required class="tsaqib-input w-full px-4 py-2.5 text-xs">
                            @foreach($daftarKomunitas as $k)
                                <option value="{{ $k['slug'] }}" {{ (Auth::user()->selected_community == $k['slug'] || $currentSlug == $k['slug']) ? 'selected' : '' }}>
                                    {{ $k['nama'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-white/80 uppercase mb-1">Isi Postingan / Deskripsi</label>
                        <textarea name="content" rows="4" required placeholder="Tuliskan materi, pengumuman, atau rincian kegiatan..."
                                  class="tsaqib-input w-full px-4 py-2.5 text-xs"></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-white/80 uppercase mb-1">Foto / Video (Opsional)</label>
                        <div class="media-drop">
                            <label class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg bg-[#01795F]/15 hover:bg-[#01795F]/25 text-[#3fd6b0] text-xs font-semibold cursor-pointer border border-[#01795F]/30 transition">
                                <i class="fa-solid fa-plus"></i> Tambahkan Foto/Video
                                <input type="file" name="media[]" multiple
                                       accept="image/jpeg,image/png,image/webp,video/mp4,video/webm" class="sr-only">
                            </label>
                            <div class="media-preview" data-preview></div>
                            <div class="media-count" data-count>Maks 6 foto (jpg/png/webp, ≤3 MB) atau 1 video (mp4/webm, ≤30 MB).</div>
                        </div>
                    </div>

                    <div class="pt-2 flex items-center justify-end space-x-2 border-t border-white/10">
                        <button type="button" data-close-create class="px-4 py-2 rounded-xl text-xs font-semibold bg-white/10 text-white/70">Batal</button>
                        <button type="submit" class="btn-submit px-5 py-2 rounded-xl text-xs font-semibold bg-[#01795F] hover:bg-[#3F704D] text-white shadow-sm">
                            <i class="fa-solid fa-paper-plane mr-1.5"></i>Terbitkan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endauth

    <!-- LIGHTBOX GALERI (navigasi, transisi, bottom-sheet mobile) -->
    <div id="lightbox">
        <div class="lb-stage">
            <div id="lb-item"></div>
            <div class="lb-counter" id="lb-counter"></div>
            <button type="button" class="lb-btn lb-close" data-action="lb-close" aria-label="Tutup"><i class="fa-solid fa-xmark"></i></button>
            <button type="button" class="lb-btn lb-prev" data-action="lb-nav" data-dir="-1" aria-label="Sebelumnya"><i class="fa-solid fa-chevron-left"></i></button>
            <button type="button" class="lb-btn lb-next" data-action="lb-nav" data-dir="1" aria-label="Berikutnya"><i class="fa-solid fa-chevron-right"></i></button>
        </div>
    </div>

    <!-- Toast (share link) -->
    <div id="toast" class="fixed bottom-6 left-1/2 -translate-x-1/2 z-[90] hidden bg-[#01795F] text-white text-xs font-semibold px-4 py-2.5 rounded-full shadow-lg"></div>

    <!-- Footer -->
    @include('partials.site-footer')

    <!-- SCRIPT: modal, lightbox, voting (AJAX) -->
    <script>
        /* ===== MODAL BUAT / EDIT ===== */
        function openCreateModal() {
            document.getElementById('create-post-modal').classList.remove('hidden');
        }
        function closeCreateModal() {
            const modal = document.getElementById('create-post-modal');
            modal.classList.add('hidden');
            resetMediaIn(modal); // bersihkan pilihan media saat dibatalkan
        }

        /* ===== MEDIA UPLOADER (thumbnail, validasi real-time, XOR foto/video) ===== */
        const MAX_IMG = 6, MAX_VID = 1, IMG_MAX = 3 * 1024 * 1024, VID_MAX = 30 * 1024 * 1024;
        const OK_EXT = { image: ['jpg', 'jpeg', 'png', 'webp'], video: ['mp4', 'webm'] };

        function classifyMedia(file) {
            const kind = file.type.startsWith('video/') ? 'video' : 'image';
            const ext = (file.name.split('.').pop() || '').toLowerCase();
            const allowed = OK_EXT[kind] || [];
            let bad = null;
            if (! allowed.includes(ext)) bad = 'Format tidak didukung';
            else if (kind === 'image' && file.size > IMG_MAX) bad = '> 3 MB';
            else if (kind === 'video' && file.size > VID_MAX) bad = '> 30 MB';
            return { file, kind, bad, url: URL.createObjectURL(file) };
        }

        function initMediaUploader(input) {
            const drop = input.closest('.media-drop');
            const preview = drop?.querySelector('[data-preview]');
            const countEl = drop?.querySelector('[data-count]');
            // Input tanpa struktur uploader (mis. input "Ganti Media" di modal edit
            // yang me-replace semua media) — lewati, jangan biarkan error di sini
            // mematikan seluruh script blok (nav, vote, edit ikut mati).
            if (! preview || ! countEl) return () => {};
            const submit = input.closest('form')?.querySelector('[type="submit"]');
            const current = [];
            const defaultCount = countEl.textContent;

            function refresh() {
                preview.innerHTML = '';
                current.forEach((it, idx) => {
                    const t = document.createElement('div');
                    t.className = 'thumb';
                    t.innerHTML = it.bad
                        ? `<div class="thumb-bad">${it.bad}</div>`
                        : (it.kind === 'video'
                            ? `<div class="thumb-video"><i class="fa-solid fa-film"></i></div>`
                            : `<img src="${it.url}" alt="">`);
                    const rm = document.createElement('div');
                    rm.className = 'thumb-remove';
                    rm.innerHTML = '<i class="fa-solid fa-xmark"></i>';
                    rm.onclick = (e) => { e.preventDefault(); current.splice(idx, 1); syncFiles(); refresh(); };
                    t.appendChild(rm);
                    preview.appendChild(t);
                });

                const imgs = current.filter(i => i.kind === 'image' && ! i.bad);
                const vids = current.filter(i => i.kind === 'video' && ! i.bad);
                const bads = current.filter(i => i.bad);
                let warn = '';
                // block = benar-benar tidak boleh submit (konflik / over-limit).
                // File ditolak (bad) TIDAK memblok: syncFiles() sudah otomatis
                // mengecualikannya, jadi user tetap bisa terbitkan dengan file
                // valid sisanya — tombol tidak nyangkut mati hanya karena 1 file
                // kebesaran/cacat format.
                let block = false;
                if (imgs.length && vids.length) { warn = 'Hanya foto ATAU video, tidak boleh campur.'; block = true; }
                else if (vids.length > MAX_VID) { warn = 'Maksimal 1 video.'; block = true; }
                else if (imgs.length > MAX_IMG) { warn = 'Maksimal 6 foto.'; block = true; }
                else if (bads.length) { warn = `${bads.length} file ditolak (format/ukuran) — tidak ikut diupload.`; }

                countEl.textContent = warn
                    ? warn
                    : (current.length === 0
                        ? defaultCount
                        : ((vids.length ? `${vids.length}/1 video` : `${imgs.length}/${MAX_IMG} foto`) + ' terpilih.'));
                countEl.className = 'media-count' + (warn ? ' media-warn' : '');
                if (submit) submit.disabled = block;
            }

            function syncFiles() {
                const dt = new DataTransfer();
                current.filter(i => ! i.bad).forEach(i => dt.items.add(i.file));
                input.files = dt.files;
            }

            // Kosongkan pilihan (dipanggil saat modal ditutup) supaya state tidak
            // mengendap dan tombol submit tidak nyangkut disable antar buka/tutup.
            function reset() {
                current.length = 0;
                input.value = '';
                refresh();
            }

            input.addEventListener('change', () => {
                for (const f of input.files) current.push(classifyMedia(f));
                syncFiles();
                refresh();
            });

            refresh();
            return reset;
        }

        /* ===== LIGHTBOX GALERI (navigasi + transisi + lazy/preload + bottom-sheet mobile) ===== */
        let lbMedia = [], lbIndex = 0;

        function openLightbox(tile) {
            const grid = tile.closest('.media-grid');
            if (! grid) return;
            try { lbMedia = JSON.parse(grid.dataset.media); } catch (e) { return; }
            lbIndex = parseInt(tile.dataset.index || '0', 10) || 0;
            document.getElementById('lightbox').classList.add('open');
            document.body.classList.add('overflow-hidden');
            lbRender(0);
        }
        function closeLightbox() {
            document.getElementById('lightbox').classList.remove('open');
            document.body.classList.remove('overflow-hidden');
            const v = document.querySelector('#lb-item video');
            if (v) v.pause();
        }
        function lbNav(dir) {
            if (! lbMedia.length) return;
            lbIndex = (lbIndex + dir + lbMedia.length) % lbMedia.length;
            lbRender(dir);
        }
        function lbRender(dir) {
            const item = document.getElementById('lb-item');
            const counter = document.getElementById('lb-counter');
            if (! lbMedia.length) return;
            counter.textContent = (lbIndex + 1) + ' / ' + lbMedia.length;
            const m = lbMedia[lbIndex];
            item.classList.add('fade');
            item.style.transform = dir === -1 ? 'translateX(-12px)' : (dir === 1 ? 'translateX(12px)' : '');
            setTimeout(() => {
                item.innerHTML = m.type === 'video'
                    ? `<video src="${m.url}" controls autoplay playsinline></video>`
                    : `<img src="${m.url}" alt="">`;
                item.classList.remove('fade');
                item.style.transform = '';
                // Preload item tetangga (lazy berikutnya) — gambar saja.
                [lbIndex + 1, lbIndex - 1].forEach(i => {
                    if (i >= 0 && i < lbMedia.length && lbMedia[i].type === 'image') {
                        const im = new Image(); im.src = lbMedia[i].url;
                    }
                });
            }, 180);
        }

        document.addEventListener('keydown', (e) => {
            if (! document.getElementById('lightbox').classList.contains('open')) return;
            if (e.key === 'Escape') closeLightbox();
            else if (e.key === 'ArrowLeft') lbNav(-1);
            else if (e.key === 'ArrowRight') lbNav(1);
        });

        const mediaResetters = new WeakMap(); // input -> reset()
        document.querySelectorAll('input[name="media[]"]').forEach(input => mediaResetters.set(input, initMediaUploader(input)));
        function resetMediaIn(scope) {
            if (scope) scope.querySelectorAll('input[name="media[]"]').forEach(i => mediaResetters.get(i)?.());
        }

        /* Batal / tombol tutup (X) — pasang via listener (kokoh & ramah CSP, bukan inline onclick) */
        document.querySelectorAll('[data-close-create]').forEach(el => el.addEventListener('click', closeCreateModal));

        /* ===== AKSI UI via DELEGASI (pengganti seluruh inline onclick — aman CSP) ===== */
        document.addEventListener('click', (e) => {
            const el = e.target.closest('[data-action]');
            if (! el) return;
            const action = el.dataset.action;
            if (action === 'open-create') { e.preventDefault(); openCreateModal(); }
            else if (action === 'open-lightbox') { e.preventDefault(); openLightbox(el); }
            else if (action === 'lb-close') { e.preventDefault(); closeLightbox(); }
            else if (action === 'lb-nav') { e.preventDefault(); lbNav(parseInt(el.dataset.dir, 10) || 0); }
        });

        /* Backdrop lightbox: klik di luar .lb-stage -> tutup */
        document.getElementById('lightbox')?.addEventListener('click', (e) => {
            if (! e.target.closest('.lb-stage')) closeLightbox();
        });

        /* Konfirmasi hapus via delegasi (pengganti onsubmit inline) */
        document.addEventListener('submit', (e) => {
            if (e.target.matches('[data-confirm]') && ! confirm(e.target.dataset.confirm)) e.preventDefault();
        });

        /* ===== VOTING (AJAX) ===== */
        (function () {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const loginUrl = '{{ route("login") }}';

            document.addEventListener('click', async (e) => {
                const btn = e.target.closest('.vote-btn');
                if (! btn) return;
                e.preventDefault();

                @guest
                /* Tamu belum login -> arahkan ke halaman login dulu. */
                window.location.href = loginUrl;
                return;
                @endguest

                const postId = btn.dataset.postId;
                const type = btn.dataset.type;
                btn.disabled = true;

                try {
                    const res = await fetch(`/posts/${postId}/vote`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: JSON.stringify({ type }),
                    });

                    if (res.status === 401) { window.location.href = loginUrl; return; }
                    if (! res.ok) throw new Error('Gagal memproses suara');
                    const data = await res.json();

                    // Perbarui kedua tombol di kartu ini: jumlah + status aktif.
                    const article = btn.closest('article');
                    article.querySelectorAll('.vote-btn').forEach((b) => {
                        const t = b.dataset.type;
                        const countEl = b.querySelector('[data-count]');
                        if (countEl) countEl.textContent = data[t === 'up' ? 'upvotes' : 'downvotes'];
                        b.classList.toggle('is-up', data.my_vote === 'up' && t === 'up');
                        b.classList.toggle('is-down', data.my_vote === 'down' && t === 'down');
                    });
                } catch (err) {
                    console.error(err);
                } finally {
                    btn.disabled = false;
                }
            });
        })();

        /* ===== Card nav + share + simpan (Tersimpan) + toast ===== */
        (function () {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const loginUrl = '{{ route("login") }}';
            let toastTimer;
            function showToast(msg) {
                const t = document.getElementById('toast');
                if (! t) return;
                t.textContent = msg;
                t.classList.remove('hidden');
                clearTimeout(toastTimer);
                toastTimer = setTimeout(() => t.classList.add('hidden'), 2000);
            }

            /* Klik area kartu (yang bukan [data-no-nav]) -> halaman detail */
            document.addEventListener('click', (e) => {
                if (e.target.closest('[data-no-nav]')) return;
                const art = e.target.closest('article[data-post-url]');
                if (art) window.location.href = art.dataset.postUrl;
            });

            /* Share: salin link + toast */
            document.addEventListener('click', (e) => {
                const btn = e.target.closest('.share-btn');
                if (! btn) return;
                e.preventDefault();
                const url = btn.dataset.url;
                if (navigator.clipboard) {
                    navigator.clipboard.writeText(url).then(() => showToast('Link tersalin!')).catch(() => showToast('Link: ' + url));
                } else {
                    showToast('Link: ' + url);
                }
            });

            /* Simpan / batal simpan (Tersimpan): toggle via AJAX — pivot post_user */
            document.addEventListener('click', async (e) => {
                const btn = e.target.closest('.save-btn');
                if (! btn) return;
                e.preventDefault();

                @guest
                window.location.href = loginUrl;
                return;
                @endguest

                btn.disabled = true;
                try {
                    const res = await fetch(`/posts/${btn.dataset.postId}/save`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' },
                        body: '{}',
                    });
                    if (res.status === 401) { window.location.href = loginUrl; return; }
                    if (! res.ok) throw new Error('Gagal menyimpan');
                    const data = await res.json();
                    btn.classList.toggle('is-active', data.saved);
                    btn.title = data.saved ? 'Hapus dari Tersimpan' : 'Simpan ke Tersimpan';
                    const label = btn.querySelector('span:not([data-count])');
                    if (label) label.textContent = data.saved ? 'Tersimpan' : 'Simpan';
                    showToast(data.saved ? 'Disimpan ke Tersimpan.' : 'Dihapus dari Tersimpan.');
                } catch (err) {
                    console.error(err);
                } finally {
                    btn.disabled = false;
                }
            });

            /* Lapor konten (post/comment) -> POST /reports */
            document.addEventListener('click', async (e) => {
                const btn = e.target.closest('.report-btn');
                if (! btn) return;
                e.preventDefault();
                const reason = prompt('Alasan laporan (singkat):', 'Spam / penyalahgunaan');
                if (! reason) return;
                try {
                    const res = await fetch('/reports', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' },
                        body: JSON.stringify({ reportable_type: btn.dataset.rt, reportable_id: btn.dataset.rid, reason }),
                    });
                    if (res.status === 401) { window.location.href = loginUrl; return; }
                    if (! res.ok) throw new Error('Gagal melaporkan');
                    const data = await res.json();
                    showToast(data.already ? (data.message || 'Sudah dilaporkan') : 'Terlapor, terima kasih.');
                } catch (err) { console.error(err); showToast('Gagal melaporkan.'); }
            });
        })();

        /* ===== MENU "..." (Edit/Hapus pada kartu feed) ===== */
        (function () {
            /* Menu "...": klik di luar panel -> tutup dropdown. */
            document.addEventListener('click', (e) => {
                document.querySelectorAll('details.k-menu[open]').forEach((d) => {
                    if (! d.contains(e.target)) d.removeAttribute('open');
                });
            });
        })();

        /* ===== SCROLL TO EDITED POST ===== */
        document.addEventListener('DOMContentLoaded', function () {
            const postId = '{{ session('scrollToPost') ?? '' }}';
            if (postId) {
                const targetCard = document.querySelector('[data-post-id="' + postId + '"]');
                if (targetCard) {
                    setTimeout(() => {
                        targetCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        targetCard.classList.add('ring-2', 'ring-[var(--gold)]');
                        setTimeout(() => {
                            targetCard.classList.remove('ring-2', 'ring-[var(--gold)]');
                        }, 2000);
                    }, 300);
                }
            }
        });

        /* ===== TOAST NOTIFICATION ===== */
        const successMessage = '{{ session('success') ?? '' }}';
        if (successMessage) {
            document.addEventListener('DOMContentLoaded', function () {
                showToast(successMessage);
            });
        }
    </script>

</body>
</html>
