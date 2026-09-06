{{-- resources/views/komunitas/index.blade.php --}}
@php($pageTitle = 'Feed Komunitas TSAQIB - SMAN 1 Bukittinggi')

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    @include('partials.theme-head')
    <style>
        /* Komunitas Feed Interactive Styling */
        .vote-btn {
            background: rgba(247, 245, 239, 0.05);
            color: rgba(247, 245, 239, 0.6);
            transition: all 0.15s ease;
        }
        .vote-btn:hover {
            background: rgba(247, 245, 239, 0.12);
            color: var(--cream);
        }
        .vote-btn.is-up {
            background: var(--green);
            color: #fff;
        }
        .vote-btn.is-up:hover {
            background: var(--green-dark);
        }
        .vote-btn.is-down {
            background: rgba(239, 68, 68, 0.2);
            color: #fca5a5;
        }

        .action-chip {
            background: rgba(247, 245, 239, 0.05);
            color: rgba(247, 245, 239, 0.6);
            transition: all 0.15s ease;
            cursor: pointer;
        }
        .action-chip:hover {
            background: rgba(247, 245, 239, 0.12);
            color: var(--cream);
        }
        .save-btn.is-active {
            background: var(--gold);
            color: var(--green-s0);
        }

        /* Media Grid */
        .media-grid { display: grid; gap: 0.35rem; border-radius: 0.75rem; overflow: hidden; }
        .media-grid.cols-1 { grid-template-columns: 1fr; }
        .media-grid.cols-2 { grid-template-columns: 1fr 1fr; }
        .media-grid.cols-3 { grid-template-columns: 1fr 1fr; }
        @media(min-width: 640px) { .media-grid.cols-3 { grid-template-columns: 1fr 1fr 1fr; } }
        .media-grid.cols-4 { grid-template-columns: 1fr 1fr; }

        .media-tile {
            position: relative;
            aspect-ratio: 1/1;
            background: rgba(247, 245, 239, 0.05);
            cursor: zoom-in;
            overflow: hidden;
        }
        .media-tile img, .media-tile video {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
        .media-tile.single {
            aspect-ratio: auto;
            max-height: 30rem;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(13, 40, 24, 0.5);
        }
        .media-tile.single > img {
            width: auto;
            height: auto;
            max-width: 100%;
            max-height: 30rem;
            object-fit: contain;
            margin: 0 auto;
        }
        .media-more {
            position: absolute;
            inset: 0;
            z-index: 2;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(13, 40, 24, 0.7);
            color: var(--cream);
            font-weight: 800;
            font-size: 1.3rem;
            backdrop-filter: blur(2px);
        }
        .media-play {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(13, 40, 24, 0.35);
            pointer-events: none;
        }
        .media-play i {
            color: #fff;
            font-size: 1.5rem;
            filter: drop-shadow(0 2px 6px rgba(0, 0, 0, 0.6));
        }

        /* Sidebar navigation links */
        .k-nav-link {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.5rem 0.65rem;
            border-radius: 0.75rem;
            font-size: 0.8125rem;
            font-weight: 600;
            color: rgba(247, 245, 239, 0.65);
            transition: all 0.15s ease;
        }
        .k-nav-link:hover {
            background: rgba(247, 245, 239, 0.06);
            color: var(--cream);
        }
        .k-nav-link.active {
            background: rgba(1, 121, 95, 0.2);
            color: var(--gold);
            border-left: 2px solid var(--gold);
        }
        .k-count {
            margin-left: auto;
            font-size: 10px;
            font-weight: 700;
            padding: 1px 7px;
            border-radius: 999px;
            background: rgba(247, 245, 239, 0.08);
            color: rgba(247, 245, 239, 0.5);
        }
        .k-nav-link.active .k-count {
            background: rgba(201, 166, 107, 0.2);
            color: var(--gold);
        }

        /* Mobile chip pills */
        .k-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.45rem 0.85rem;
            border-radius: 999px;
            border: 1px solid rgba(247, 245, 239, 0.12);
            background: rgba(247, 245, 239, 0.04);
            color: rgba(247, 245, 239, 0.75);
            font-size: 11px;
            font-weight: 600;
            transition: all 0.15s ease;
        }
        .k-chip:hover {
            background: rgba(247, 245, 239, 0.09);
            color: var(--cream);
        }
        .k-chip.active {
            background: rgba(1, 121, 95, 0.25);
            color: var(--gold);
            border-color: rgba(201, 166, 107, 0.4);
        }

        /* Post context menu */
        .k-menu { position: relative; }
        .k-menu > summary { list-style: none; }
        .k-menu > summary::-webkit-details-marker { display: none; }
        .k-menu-panel {
            position: absolute;
            right: 0;
            top: calc(100% + 4px);
            min-width: 9rem;
            z-index: 30;
            background: #143520;
            border: 1px solid rgba(247, 245, 239, 0.15);
            border-radius: 0.75rem;
            padding: 4px;
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.6);
        }
        .k-menu-item {
            display: flex;
            align-items: center;
            gap: 0.55rem;
            width: 100%;
            text-align: left;
            padding: 0.5rem 0.65rem;
            border-radius: 0.5rem;
            font-size: 12px;
            font-weight: 600;
            transition: all 0.15s ease;
        }

        /* Uploader preview */
        .media-drop { border: 1px dashed rgba(201, 166, 107, 0.3); border-radius: 0.75rem; padding: 0.75rem; }
        .media-preview { display: grid; grid-template-columns: repeat(auto-fill, 76px); gap: 0.4rem; margin-top: 0.4rem; min-height: 80px; }
        .thumb { position: relative; aspect-ratio: 1/1; border-radius: 0.5rem; overflow: hidden; background: rgba(247, 245, 239, 0.05); border: 1px solid rgba(247, 245, 239, 0.1); }
        .thumb img, .thumb video { width: 100%; height: 100%; object-fit: cover; }
        .thumb-remove { position: absolute; top: 2px; right: 2px; width: 20px; height: 20px; border-radius: 999px; background: rgba(239, 68, 68, 0.9); color: #fff; font-size: 10px; display: flex; align-items: center; justify-content: center; cursor: pointer; }
    </style>
</head>
<body class="text-[var(--cream)] font-sans antialiased min-h-screen flex flex-col relative overflow-x-hidden">

    <!-- Unified TSAQIB Navbar -->
    @include('partials.navbar')

    <main class="flex-1 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10 space-y-6 w-full">

        <!-- Title Banner -->
        <div class="tsaqib-card p-6 sm:p-7">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <span class="eyebrow-pill eyebrow-pill-gold mb-2">
                        <i class="fa-solid fa-users text-[10px]"></i> Linimasa Komunitas
                    </span>
                    <h1 class="text-2xl sm:text-3xl font-display font-bold text-[var(--cream)] mt-1">
                        Timeline Feed Komunitas
                        @if($currentSlug !== 'semua')
                            <span class="text-[var(--gold)]">— {{ collect($daftarKomunitas)->firstWhere('slug', $currentSlug)['nama'] ?? '' }}</span>
                        @endif
                    </h1>
                    <p class="text-white/60 text-xs sm:text-sm mt-1">
                        Kumpulan kegiatan, karya, karya tulis, dan pengumuman dari 13 circle FSI SMAN 1 Bukittinggi.
                    </p>
                </div>
                @auth
                    <button type="button" data-action="open-create" class="btn-primary self-start sm:self-auto py-2.5 px-4 text-xs">
                        <i class="fa-solid fa-plus text-[11px]"></i>
                        <span>Buat Postingan</span>
                    </button>
                @endauth
            </div>
        </div>

        {{-- Grid 3 Kolom: Sidebar Kiri (lg+) | Feed Tengah | Postingan Terbaru (xl+) --}}
        <div class="lg:grid lg:grid-cols-[240px_minmax(0,1fr)] xl:grid-cols-[240px_minmax(0,1fr)_260px] lg:gap-6 xl:gap-8 items-start">

            {{-- ============ SIDEBAR KIRI (lg+) ============ --}}
            <aside class="hidden lg:block sticky top-24">
                <nav class="tsaqib-card p-2.5 space-y-1">
                    <p class="px-2.5 pt-1.5 pb-1 text-[10px] font-bold uppercase tracking-wider text-white/40">Daftar Circle</p>

                    <a href="{{ route('komunitas', 'semua') }}"
                       class="k-nav-link {{ $currentSlug === 'semua' ? 'active' : '' }}"
                       {{ $currentSlug === 'semua' ? 'aria-current="page"' : '' }}>
                        <span class="w-8 h-8 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-layer-group text-[var(--gold)] text-xs"></i>
                        </span>
                        <span class="min-w-0 flex-1 truncate">Semua Circle</span>
                        <span class="k-count">{{ $totalSemua }}</span>
                    </a>

                    @foreach($komunitasSidebar as $k)
                        <a href="{{ route('komunitas', $k['slug']) }}"
                           class="k-nav-link {{ $currentSlug === $k['slug'] ? 'active' : '' }}"
                           {{ $currentSlug === $k['slug'] ? 'aria-current="page"' : '' }}>
                            <img src="{{ asset($k['image']) }}" alt="" loading="lazy" onerror="this.remove()"
                                 class="w-8 h-8 rounded-lg object-cover shrink-0 bg-white/5 border border-white/10">
                            <span class="min-w-0 flex-1 truncate">{{ $k['nama'] }}</span>
                            <span class="k-count">{{ $k['total'] }}</span>
                        </a>
                    @endforeach
                </nav>
            </aside>

            {{-- ============ KOLOM TENGAH: FEED ============ --}}
            <div class="min-w-0 space-y-5">

                {{-- Mobile chip filters (< lg) --}}
                <div class="lg:hidden flex gap-2 overflow-x-auto pb-1" style="scrollbar-width: none;">
                    <a href="{{ route('komunitas', 'semua') }}"
                       class="k-chip shrink-0 whitespace-nowrap {{ $currentSlug === 'semua' ? 'active' : '' }}">
                        <i class="fa-solid fa-layer-group text-[10px] text-[var(--gold)]"></i> Semua
                        <span class="opacity-60 ml-0.5">{{ $totalSemua }}</span>
                    </a>
                    @foreach($komunitasSidebar as $k)
                        <a href="{{ route('komunitas', $k['slug']) }}"
                           class="k-chip shrink-0 whitespace-nowrap {{ $currentSlug === $k['slug'] ? 'active' : '' }}">
                            <img src="{{ asset($k['image']) }}" alt="" loading="lazy" onerror="this.remove()"
                                 class="w-4 h-4 rounded object-cover">
                            {{ $k['nama'] }} <span class="opacity-60 ml-0.5">{{ $k['total'] }}</span>
                        </a>
                    @endforeach
                </div>

                {{-- Search bar --}}
                <div class="tsaqib-card p-3 sm:p-3.5">
                    <form action="{{ request()->url() }}" method="GET" role="search" class="flex items-center gap-2">
                        <div class="relative flex-1 input-glow-group">
                            <i class="fa-solid fa-magnifying-glass input-icon absolute left-3.5 top-1/2 -translate-y-1/2 text-white/40 text-xs pointer-events-none"></i>
                            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari postingan atau materi..."
                                   class="tsaqib-input w-full pl-9 pr-3 py-2 text-xs">
                        </div>
                        @if(request('q'))
                            <a href="{{ request()->url() }}" class="px-3 py-2 rounded-xl text-xs font-semibold bg-white/10 text-white/70 hover:text-white whitespace-nowrap">Reset</a>
                        @endif
                        <button type="submit" class="btn-primary py-2 px-3.5 text-xs whitespace-nowrap">
                            <i class="fa-solid fa-magnifying-glass sm:hidden"></i>
                            <span class="hidden sm:inline">Cari</span>
                        </button>
                    </form>
                </div>

                @if(session('success'))
                    <div class="p-3.5 rounded-xl bg-[#01795F]/15 text-[#3fd6b0] border border-[#01795F]/30 text-xs font-semibold flex items-center gap-2">
                        <i class="fa-solid fa-circle-check"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="p-3.5 rounded-xl bg-red-500/15 text-red-300 border border-red-500/30 text-xs font-semibold">
                        {{ $errors->first() }}
                    </div>
                @endif

                {{-- Sort Tabs --}}
                <div class="tsaqib-card p-1.5 flex items-center gap-1.5">
                    @foreach(['recent' => ['Terbaru', 'fa-clock'], 'popular' => ['Terpopuler', 'fa-fire']] as $sortKey => $tab)
                        <a href="{{ request()->fullUrlWithQuery(['sort' => $sortKey, 'page' => 1]) }}"
                           class="flex-1 text-center py-2 rounded-lg text-xs font-bold uppercase tracking-wider transition {{ ($sort ?? request('sort', 'recent')) === $sortKey ? 'bg-[#01795F] text-white shadow' : 'text-white/60 hover:text-white hover:bg-white/5' }}">
                            <i class="fa-solid {{ $tab[1] }} mr-1.5"></i>{{ $tab[0] }}
                        </a>
                    @endforeach
                </div>

                {{-- Posts Timeline Feed --}}
                <div class="space-y-4">
                    @forelse($posts as $post)
                        <article class="tsaqib-card overflow-hidden cursor-pointer transition duration-150"
                                 data-post-id="{{ $post->id }}"
                                 data-post-url="{{ route('komunitas.post.show', $post->id) }}">
                            @include('komunitas._post-card', ['post' => $post, 'showManage' => true, 'compact' => true])
                        </article>
                    @empty
                        <div class="tsaqib-card-flat p-10 text-center text-white/45 text-xs space-y-2">
                            <i class="fa-regular fa-folder-open text-3xl text-white/20 block"></i>
                            <p>Belum ada postingan di kategori ini.</p>
                            @auth
                                <button type="button" data-action="open-create" class="btn-gold py-1.5 px-3 text-xs mt-2">
                                    <i class="fa-solid fa-plus text-[10px]"></i> Buat Postingan Pertama
                                </button>
                            @endauth
                        </div>
                    @endforelse
                </div>

                {{-- Pagination --}}
                @if ($posts->hasPages())
                    <div class="pt-2">
                        {{ $posts->links('partials.pagination') }}
                    </div>
                @endif

            </div>

            {{-- ============ KOLOM KANAN (xl+): WIDGET ============ --}}
            <aside class="hidden xl:block sticky top-24 space-y-4">
                @include('komunitas._adab-widget')
                @include('komunitas._recent-posts')
            </aside>
        </div>

    </main>

    <!-- Floating Action Button (+) -->
    @auth
        <div class="fixed bottom-6 right-6 z-40">
            <button type="button" data-action="open-create"
                    class="w-13 h-13 rounded-full bg-[var(--gold)] text-[var(--green-s0)] shadow-2xl flex items-center justify-center text-xl font-bold transition-all transform hover:scale-110 hover:brightness-110 focus:outline-none"
                    title="Buat Postingan Baru">
                <i class="fa-solid fa-plus"></i>
            </button>
        </div>

        <!-- CREATE POST MODAL -->
        <div id="create-post-modal" class="hidden fixed inset-0 z-[80] flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm">
            <div class="bg-[#143520] border border-white/15 rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-4 max-h-[90vh] overflow-y-auto relative">
                <div class="flex items-center justify-between pb-3 border-b border-white/10">
                    <h3 class="font-display font-bold text-[var(--cream)] text-base flex items-center space-x-2">
                        <i class="fa-solid fa-pen-to-square text-[var(--gold)]"></i>
                        <span>Buat Postingan Baru</span>
                    </h3>
                    <button type="button" data-close-create class="text-white/40 hover:text-white p-1">
                        <i class="fa-solid fa-xmark text-base"></i>
                    </button>
                </div>

                <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-white/80 uppercase mb-1">Judul Postingan</label>
                        <input type="text" name="title" required placeholder="Contoh: Dokumentasi Kajian &amp; Mentoring..."
                               class="tsaqib-input w-full px-3.5 py-2.5 text-xs">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-white/80 uppercase mb-1">Kategori Komunitas</label>
                        <select name="community_slug" required class="tsaqib-input w-full px-3.5 py-2.5 text-xs">
                            @foreach($daftarKomunitas as $k)
                                <option value="{{ $k['slug'] }}" {{ (Auth::user()->selected_community == $k['slug'] || $currentSlug == $k['slug']) ? 'selected' : '' }}>
                                    {{ $k['nama'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-white/80 uppercase mb-1">Isi Postingan / Deskripsi</label>
                        <textarea name="content" rows="4" required placeholder="Tuliskan materi, pengumuman, atau catatan kegiatan..."
                                  class="tsaqib-input w-full px-3.5 py-2.5 text-xs"></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-white/80 uppercase mb-1">Foto / Video (Opsional)</label>
                        <div class="media-drop">
                            <label class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-[#01795F]/20 hover:bg-[#01795F]/30 text-[#3fd6b0] text-xs font-semibold cursor-pointer border border-[#01795F]/40 transition">
                                <i class="fa-solid fa-plus text-[10px]"></i> Unggah Media
                                <input type="file" name="media[]" multiple accept="image/jpeg,image/png,image/webp,video/mp4,video/webm" class="sr-only">
                            </label>
                            <div class="media-preview" data-preview></div>
                            <div class="text-[10px] text-white/40 mt-1" data-count>Maks 6 foto (jpg/png/webp, ≤3 MB) atau 1 video (mp4/webm, ≤30 MB).</div>
                        </div>
                    </div>

                    <div class="pt-2 flex items-center justify-end space-x-2 border-t border-white/10">
                        <button type="button" data-close-create class="btn-outline py-2 px-4 text-xs">Batal</button>
                        <button type="submit" class="btn-primary py-2 px-5 text-xs">
                            <i class="fa-solid fa-paper-plane mr-1 text-[10px]"></i>Terbitkan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endauth

    <!-- Lightbox Gallery Modal -->
    <div id="lightbox">
        <div class="lb-stage">
            <div id="lb-item"></div>
            <div class="lb-counter" id="lb-counter"></div>
            <button type="button" class="lb-btn lb-close" data-action="lb-close" aria-label="Tutup"><i class="fa-solid fa-xmark"></i></button>
            <button type="button" class="lb-btn lb-prev" data-action="lb-nav" data-dir="-1" aria-label="Sebelumnya"><i class="fa-solid fa-chevron-left"></i></button>
            <button type="button" class="lb-btn lb-next" data-action="lb-nav" data-dir="1" aria-label="Berikutnya"><i class="fa-solid fa-chevron-right"></i></button>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="fixed bottom-6 left-1/2 -translate-x-1/2 z-[90] hidden bg-[#01795F] text-white text-xs font-semibold px-4 py-2.5 rounded-full shadow-2xl border border-white/20"></div>

    <!-- Global Footer -->
    @include('partials.site-footer')

    <!-- JavaScript Handlers -->
    <script>
        function openCreateModal() { document.getElementById('create-post-modal')?.classList.remove('hidden'); }
        function closeCreateModal() {
            const modal = document.getElementById('create-post-modal');
            if (modal) { modal.classList.add('hidden'); resetMediaIn(modal); }
        }
        document.querySelectorAll('[data-close-create]').forEach(el => el.addEventListener('click', closeCreateModal));

        // Lightbox handlers
        let lbMedia = [], lbIndex = 0;
        function openLightbox(tile) {
            const grid = tile.closest('.media-grid');
            if (!grid) return;
            try { lbMedia = JSON.parse(grid.dataset.media); } catch (e) { return; }
            lbIndex = parseInt(tile.dataset.index || '0', 10) || 0;
            document.getElementById('lightbox').classList.add('open');
            document.body.classList.add('overflow-hidden');
            lbRender(0);
        }
        function closeLightbox() {
            document.getElementById('lightbox')?.classList.remove('open');
            document.body.classList.remove('overflow-hidden');
            const v = document.querySelector('#lb-item video');
            if (v) v.pause();
        }
        function lbNav(dir) {
            if (!lbMedia.length) return;
            lbIndex = (lbIndex + dir + lbMedia.length) % lbMedia.length;
            lbRender(dir);
        }
        function lbRender(dir) {
            const item = document.getElementById('lb-item');
            const counter = document.getElementById('lb-counter');
            if (!item || !counter || !lbMedia.length) return;
            counter.textContent = (lbIndex + 1) + ' / ' + lbMedia.length;
            const m = lbMedia[lbIndex];
            item.classList.add('fade');
            setTimeout(() => {
                item.innerHTML = m.type === 'video'
                    ? `<video src="${m.url}" controls autoplay playsinline class="max-h-[80vh] rounded-lg"></video>`
                    : `<img src="${m.url}" alt="" class="max-h-[80vh] rounded-lg">`;
                item.classList.remove('fade');
            }, 150);
        }

        document.addEventListener('keydown', (e) => {
            if (!document.getElementById('lightbox')?.classList.contains('open')) return;
            if (e.key === 'Escape') closeLightbox();
            else if (e.key === 'ArrowLeft') lbNav(-1);
            else if (e.key === 'ArrowRight') lbNav(1);
        });

        document.addEventListener('click', (e) => {
            const el = e.target.closest('[data-action]');
            if (!el) return;
            const action = el.dataset.action;
            if (action === 'open-create') { e.preventDefault(); openCreateModal(); }
            else if (action === 'open-lightbox') { e.preventDefault(); openLightbox(el); }
            else if (action === 'lb-close') { e.preventDefault(); closeLightbox(); }
            else if (action === 'lb-nav') { e.preventDefault(); lbNav(parseInt(el.dataset.dir, 10) || 0); }
        });

        // Voting & Post interactions
        (function () {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const loginUrl = '{{ route("login") }}';
            let toastTimer;
            function showToast(msg) {
                const t = document.getElementById('toast');
                if (!t) return;
                t.textContent = msg;
                t.classList.remove('hidden');
                clearTimeout(toastTimer);
                toastTimer = setTimeout(() => t.classList.add('hidden'), 2200);
            }

            // Navigate to post detail when clicking card
            document.addEventListener('click', (e) => {
                if (e.target.closest('[data-no-nav]')) return;
                const art = e.target.closest('article[data-post-url]');
                if (art) window.location.href = art.dataset.postUrl;
            });

            // Vote button handler
            document.addEventListener('click', async (e) => {
                const btn = e.target.closest('.vote-btn');
                if (!btn) return;
                e.preventDefault();
                @guest window.location.href = loginUrl; return; @endguest

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
                    if (!res.ok) throw new Error();
                    const data = await res.json();
                    const article = btn.closest('article');
                    article?.querySelectorAll('.vote-btn').forEach((b) => {
                        const t = b.dataset.type;
                        const countEl = b.querySelector('[data-count]');
                        if (countEl) countEl.textContent = data[t === 'up' ? 'upvotes' : 'downvotes'];
                        b.classList.toggle('is-up', data.my_vote === 'up' && t === 'up');
                        b.classList.toggle('is-down', data.my_vote === 'down' && t === 'down');
                    });
                    if (window.triggerUpvoteAnimation) {
                        window.triggerUpvoteAnimation(btn, data.my_vote === 'up');
                    }
                } catch (err) {
                    console.error(err);
                } finally {
                    btn.disabled = false;
                }
            });

            // Share link
            document.addEventListener('click', (e) => {
                const btn = e.target.closest('.share-btn');
                if (!btn) return;
                e.preventDefault();
                const url = btn.dataset.url;
                if (navigator.clipboard) {
                    navigator.clipboard.writeText(url).then(() => showToast('Link tersalin!')).catch(() => showToast('Link: ' + url));
                } else {
                    showToast('Link: ' + url);
                }
            });

            // Save post
            document.addEventListener('click', async (e) => {
                const btn = e.target.closest('.save-btn');
                if (!btn) return;
                e.preventDefault();
                @guest window.location.href = loginUrl; return; @endguest

                btn.disabled = true;
                try {
                    const res = await fetch(`/posts/${btn.dataset.postId}/save`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' },
                        body: '{}',
                    });
                    if (res.status === 401) { window.location.href = loginUrl; return; }
                    if (!res.ok) throw new Error();
                    const data = await res.json();
                    btn.classList.toggle('is-active', data.saved);
                    showToast(data.saved ? 'Tersimpan ke Koleksi' : 'Dihapus dari Koleksi');
                } catch (err) {
                    console.error(err);
                } finally {
                    btn.disabled = false;
                }
            });
        })();

        // Media uploader preview handler
        function resetMediaIn(modal) {
            const input = modal.querySelector('input[type="file"]');
            const preview = modal.querySelector('[data-preview]');
            if (input) input.value = '';
            if (preview) preview.innerHTML = '';
        }
        document.querySelectorAll('input[name="media[]"]').forEach(input => {
            input.addEventListener('change', () => {
                const preview = input.closest('.media-drop')?.querySelector('[data-preview]');
                if (!preview) return;
                preview.innerHTML = '';
                Array.from(input.files).forEach(f => {
                    const div = document.createElement('div');
                    div.className = 'thumb';
                    if (f.type.startsWith('image/')) {
                        const img = document.createElement('img');
                        img.src = URL.createObjectURL(f);
                        div.appendChild(img);
                    } else {
                        div.innerHTML = '<div class="w-full h-full flex items-center justify-center text-[var(--gold)]"><i class="fa-solid fa-film"></i></div>';
                    }
                    preview.appendChild(div);
                });
            });
        });
    </script>
</body>
</html>
