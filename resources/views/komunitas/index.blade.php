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
    .media-tile.single{ aspect-ratio:16/10; max-height:24rem; }
    .media-tile img,.media-tile video{ width:100%; height:100%; object-fit:cover; display:block; }
    .media-tile.single .media-blur{ position:absolute; inset:0; width:100%; height:100%; object-fit:cover; object-position:center center; filter:blur(24px) saturate(1.2); transform:scale(1.18); transform-origin:center; z-index:0; }
    .media-grid.cols-1 .media-tile > img:not(.media-blur){ position:absolute; inset:0; margin:auto; width:auto; height:auto; max-width:100%; max-height:100%; z-index:1; background:transparent; }
    .media-grid.cols-1 .media-tile > video{ object-fit:contain; object-position:center center; background:#000; }
    .media-more{ position:absolute; inset:0; display:flex; align-items:center; justify-content:center;
        background:rgba(16,20,15,.65); color:var(--cream); font-weight:800; font-size:1.4rem; }
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

    /* ===== LAYOUT 3-KOLOM (Reddit-style) =====
       xl: sidebar kiri (sticky) | feed (center) | sidebar kanan (sticky).
       <= lg: turun ke 1 kolom — sidebar kiri jadi chip row horizontal di atas
       feed, sidebar kanan pindah ke bawah feed. */
    .komunitas-shell{ display:grid; grid-template-columns:1fr; gap:1.25rem; }
    @media(min-width:1280px){
        .komunitas-shell{
            grid-template-columns:16rem minmax(0,1fr) 18rem;
            align-items:start;
        }
    }
    .komunitas-aside{
        position:sticky;
        top:5.5rem;          /* di bawah navbar sticky (h-16/h-20 + jeda) */
        max-height:calc(100vh - 6.5rem);
        overflow-y:auto;
        scrollbar-width:thin;
    }
    @media(max-width:1279px){ .komunitas-aside{ position:static; max-height:none; overflow:visible; } }
    .komunitas-aside::-webkit-scrollbar{ width:6px; }
    .komunitas-aside::-webkit-scrollbar-thumb{ background:rgba(247,245,239,.12); border-radius:999px; }

    /* Item daftar komunitas (sidebar kiri) */
    .kom-item{ display:flex; align-items:center; gap:.6rem; padding:.55rem .65rem; border-radius:.65rem;
        font-size:.8rem; font-weight:600; color:rgba(247,245,239,.7);
        transition:background .15s ease, color .15s ease; }
    .kom-item:hover{ background:rgba(247,245,239,.05); color:var(--cream); }
    .kom-item.is-active{ background:rgba(1,121,95,.2); color:var(--cream); }
    .kom-item .kom-ikon{ width:28px; height:28px; border-radius:.5rem; object-fit:cover; background:rgba(247,245,239,.06);
        display:flex; align-items:center; justify-content:center; color:var(--gold); flex-shrink:0; }
    .kom-item .kom-nama{ flex:1; min-width:0; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
    .kom-item .kom-count{ font-size:10px; padding:1px 7px; border-radius:999px;
        background:rgba(247,245,239,.08); color:rgba(247,245,239,.6); flex-shrink:0; }
    .kom-item.is-active .kom-count{ background:rgba(201,166,107,.2); color:var(--gold); }

    /* Chip row (sidebar kiri di mobile/tablet) — satu baris, scroll horizontal halus.
       -webkit-overflow-scrolling:touch = momentum scroll di iOS. */
    .kom-chips{ display:flex; gap:.5rem; overflow-x:auto; padding-bottom:.25rem; scrollbar-width:none; -webkit-overflow-scrolling:touch; }
    .kom-chips::-webkit-scrollbar{ display:none; }
    .kom-chip{ display:inline-flex; align-items:center; gap:.4rem; padding:.45rem .85rem; border-radius:999px;
        font-size:.72rem; font-weight:700; white-space:nowrap; flex-shrink:0;
        background:rgba(247,245,239,.05); color:rgba(247,245,239,.75);
        border:1px solid rgba(247,245,239,.1); transition:background .15s ease, color .15s ease, border-color .15s ease; }
    .kom-chip:hover{ background:rgba(247,245,239,.1); color:var(--cream); }
    .kom-chip.is-active{ background:rgba(1,121,95,.28); color:var(--gold); border-color:rgba(201,166,107,.35); }
    /* Mobile: chip diramping (padding/font lebih kecil) agar lebih banyak muat
       per baris. min-height tetap dipertahankan untuk target tap yang layak. */
    @media(max-width:1023px){
        .kom-chip{ padding:.28rem .5rem; font-size:.61rem; gap:.25rem; min-height:36px; line-height:1; }
        .kom-chip i{ font-size:.58rem; }
    }

    /* FIX: grid items default min-width:auto → isi lebar (chip row) bisa
       melebar keluar viewport & memicu scroll halaman. min-width:0 memaksa
       .kom-chips scroll internal, bukan menggeser seluruh halaman. */
    .komunitas-shell > *{ min-width:0; }

    /* (Toggle sort Terbaru/Terpopuler .kom-sort dihapus — feed selalu Terbaru.) */

    /* ===== "Postingan Terbaru": accordion inline (mobile/tablet < xl) =====
       Menggantikan drawer geser. Header (bolt + chevron) selalu tampil;
       body collapse by default (atribut hidden). Chevron berputar saat buka. */
    .kom-acc{ border-radius:.85rem; border:1px solid rgba(247,245,239,.08);
        background:rgba(247,245,239,.025); overflow:hidden; }
    .kom-acc-head{ width:100%; display:flex; align-items:center; justify-content:space-between;
        gap:.5rem; padding:.7rem .9rem; background:none; border:none; cursor:pointer;
        font-size:.72rem; font-weight:700; color:var(--cream); }
    .kom-acc-chev{ font-size:.7rem; color:rgba(247,245,239,.45); transition:transform .2s ease; }
    .kom-acc.is-open .kom-acc-chev{ transform:rotate(180deg); }
    .kom-acc-body{ padding:.25rem .6rem .6rem; }

    /* FAB (+) — rounded-square 44px (lebih kecil dari lingkaran 56px lama). */
    @media(max-width:1023px){
        .kom-fab{ width:44px !important; height:44px !important; border-radius:.85rem !important; font-size:1.1rem !important; }
    }

    /* Baris postingan terbaru (sidebar kanan) */
    .recent-row{ display:flex; gap:.6rem; padding:.55rem .4rem; border-radius:.5rem; transition:background .15s ease; }
    .recent-row:hover{ background:rgba(247,245,239,.04); }
    .recent-thumb{ width:40px; height:40px; border-radius:.4rem; object-fit:cover; background:rgba(247,245,239,.05);
        flex-shrink:0; display:flex; align-items:center; justify-content:center; color:rgba(247,245,239,.25); }

    /* (Drawer "Postingan Terbaru" lama dihapus — kini inline accordion .kom-acc.) */
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

    <main class="flex-1 w-full max-w-7xl mx-auto px-4 sm:px-6 py-8 sm:py-10">
        {{-- Shell 3-kolom: sidebar kiri | feed | sidebar kanan (xl+).
             Di bawah xl, turun 1 kolom: chips komunitas di atas, sidebar kanan di bawah. --}}
        <div class="komunitas-shell">

        {{-- ============ SIDEBAR KIRI (komunitas) ============ --}}
        {{-- Mobile/tablet: chip row horizontal di atas feed. --}}
        <aside class="xl:hidden">
            <div class="kom-chips">
                <a href="{{ route('komunitas', 'semua') }}"
                   class="kom-chip {{ $currentSlug === 'semua' ? 'is-active' : '' }}">
                    <i class="fa-solid fa-layer-group"></i> Semua
                    <span class="opacity-60">{{ $totalSemua }}</span>
                </a>
                @foreach($komunitasSidebar as $k)
                    <a href="{{ route('komunitas', $k['slug']) }}"
                       class="kom-chip {{ $currentSlug === $k['slug'] ? 'is-active' : '' }}">
                        {{ $k['nama'] }}
                        <span class="opacity-60">{{ $k['total'] }}</span>
                    </a>
                @endforeach
            </div>
        </aside>

        {{-- Desktop (xl+): daftar vertikal sticky. --}}
        <aside class="komunitas-aside hidden xl:block">
            <div class="tsaqib-card p-3">
                <p class="px-2 mb-1.5 text-[10px] font-bold uppercase tracking-wider text-white/40 flex items-center gap-1.5">
                    <i class="fa-solid fa-users text-[var(--gold)]"></i> Komunitas TSAQIB
                </p>
                <div class="space-y-0.5">
                    <a href="{{ route('komunitas', 'semua') }}"
                       class="kom-item {{ $currentSlug === 'semua' ? 'is-active' : '' }}">
                        <span class="kom-ikon"><i class="fa-solid fa-layer-group text-xs"></i></span>
                        <span class="kom-nama">Semua Komunitas</span>
                        <span class="kom-count">{{ $totalSemua }}</span>
                    </a>
                    @foreach($komunitasSidebar as $k)
                        <a href="{{ route('komunitas', $k['slug']) }}"
                           class="kom-item {{ $currentSlug === $k['slug'] ? 'is-active' : '' }}">
                            @if(!empty($k['image']) && file_exists(public_path($k['image'])))
                                <img src="{{ asset($k['image']) }}" alt="{{ $k['nama'] }}" class="kom-ikon" style="object-fit:cover;">
                            @else
                                <span class="kom-ikon"><i class="fa-solid fa-hashtag text-xs"></i></span>
                            @endif
                            <span class="kom-nama">{{ $k['nama'] }}</span>
                            <span class="kom-count">{{ $k['total'] }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </aside>

        {{-- ============ FEED (center) ============ --}}
        <div class="min-w-0 space-y-6">

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
                    <p class="text-white/50 text-xs mt-0.5">Kumpulan postingan kegiatan, pengumuman, dan karya 13 komunitas FSI</p>
                </div>
                @auth
                    <button onclick="openCreateModal()" class="hidden sm:inline-flex items-center space-x-2 px-4 py-2.5 rounded-xl bg-[#01795F] hover:bg-[#3F704D] text-white font-semibold text-xs shadow-sm transition">
                        <i class="fa-solid fa-plus"></i>
                        <span>Buat Postingan</span>
                    </button>
                @endauth
            </div>
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

        {{-- "Postingan Terbaru" sebagai AKORDION inline (mobile/tablet < xl), collapsed
             by default. Header (bolt + chevron) selalu tampil; body dibuka via JS.
             Menggantikan drawer geser lama (boros ruang vertikal & state tersebar). --}}
        <div class="kom-acc xl:hidden" id="recent-acc">
            <button type="button" class="kom-acc-head" id="recent-trigger"
                    aria-expanded="false" aria-controls="recent-acc-body">
                <span class="flex items-center gap-2"><i class="fa-solid fa-bolt text-[var(--gold)]"></i> Postingan Terbaru</span>
                <i class="fa-solid fa-chevron-down kom-acc-chev"></i>
            </button>
            <div class="kom-acc-body" id="recent-acc-body" hidden>
                @if($recentPosts->isNotEmpty())
                    <div class="space-y-0.5">
                        @foreach($recentPosts as $rp)
                            @php($rpThumb = $rp->media->first())
                            <a href="{{ route('komunitas.post.show', $rp->id) }}" class="recent-row group">
                                @if($rpThumb && $rpThumb->type === 'image')
                                    <img src="{{ $rpThumb->url }}" alt="" class="recent-thumb" style="object-fit:cover;">
                                @else
                                    <span class="recent-thumb"><i class="fa-solid fa-image text-sm"></i></span>
                                @endif
                                <div class="min-w-0 flex-1">
                                    <span class="block text-[10px] font-bold uppercase tracking-wide text-[var(--gold)] truncate">{{ $namaKomunitas[$rp->community_slug] ?? $rp->community_slug }}</span>
                                    <p class="text-xs font-semibold text-white/80 group-hover:text-[var(--cream)] line-clamp-1 leading-snug">{{ $rp->title }}</p>
                                    <span class="flex items-center gap-2 text-[10px] text-white/40 mt-0.5">
                                        <span><i class="fa-regular fa-clock mr-0.5"></i>{{ $rp->created_at->diffForHumans() }}</span>
                                        @if($rp->comments_count > 0)
                                            <span><i class="fa-regular fa-comment mr-0.5"></i>{{ $rp->comments_count }}</span>
                                        @endif
                                        <span><i class="fa-solid fa-thumbs-up mr-0.5"></i>{{ $rp->upvotes }}</span>
                                    </span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                    <a href="{{ route('komunitas', 'semua') }}" class="block text-center text-[11px] font-bold text-[var(--gold)] hover:underline mt-2 pt-2 border-t border-white/10">
                        Lihat Semua <i class="fa-solid fa-arrow-right text-[9px]"></i>
                    </a>
                @else
                    <p class="px-1 py-4 text-[11px] text-white/40 text-center">Belum ada postingan.</p>
                @endif
            </div>
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

        {{-- (Toggle sort Terbaru/Terpopuler dihapus — feed kini selalu Terbaru.) --}}

        <!-- POSTS TIMELINE FEED -->
        <div class="space-y-4">
            @forelse($posts as $post)
                <article class="tsaqib-card p-6 transition duration-150 cursor-pointer"
                         data-post-url="{{ route('komunitas.post.show', $post->id) }}">
                    @include('komunitas._post-card', ['post' => $post, 'showManage' => true])

                    <!-- EDIT MODAL FORM -->
                    @if(Auth::check() && (Auth::id() === $post->user_id || Auth::user()->role === 'admin'))
                        <div id="edit-modal-{{ $post->id }}" class="hidden mt-4 pt-4 border-t border-white/10" data-no-nav>
                            <form action="{{ route('posts.update', $post->id) }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                                @csrf
                                @method('PUT')
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-white/50 mb-1">Judul Postingan</label>
                                    <input type="text" name="title" value="{{ $post->title }}" required class="tsaqib-input w-full px-3 py-2 text-xs font-bold">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-white/50 mb-1">Isi Postingan</label>
                                    <textarea name="content" rows="3" required class="tsaqib-input w-full px-3 py-2 text-xs">{{ $post->content }}</textarea>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-white/50 mb-1">Media</label>
                                    @if ($post->media->isNotEmpty())
                                        <div class="flex gap-1 flex-wrap mb-2">
                                            @foreach ($post->media as $mItem)
                                                <div class="w-9 h-9 rounded overflow-hidden border border-white/10 bg-white/5 flex items-center justify-center">
                                                    @if ($mItem->isVideo())
                                                        <i class="fa-solid fa-film text-xs text-[var(--gold)]"></i>
                                                    @else
                                                        <img src="{{ $mItem->url }}" class="w-full h-full object-cover">
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                    <div class="media-drop">
                                        <label class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg bg-[#01795F]/15 hover:bg-[#01795F]/25 text-[#3fd6b0] text-xs font-semibold cursor-pointer border border-[#01795F]/30 transition">
                                            <i class="fa-solid fa-plus"></i> Tambahkan Foto/Video
                                            <input type="file" name="media[]" multiple
                                                   accept="image/jpeg,image/png,image/webp,video/mp4,video/webm" class="sr-only">
                                        </label>
                                        <div class="media-preview" data-preview></div>
                                        <div class="media-count" data-count>Biarkan kosong untuk mempertahankan media lama. Upload baru = ganti semua.</div>
                                    </div>
                                </div>
                                <div class="flex justify-end space-x-2 pt-2">
                                    <button type="button" onclick="toggleEditModal('{{ $post->id }}')" class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-white/10 text-white/70">Batal</button>
                                    <button type="submit" class="btn-submit px-4 py-1.5 rounded-lg text-xs font-semibold bg-[#01795F] text-white">Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>
                    @endif
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

        </div>{{-- /FEED center --}}

        {{-- ============ SIDEBAR KANAN (Postingan Terbaru, site-wide) ============
             Desktop (xl+): sticky. Mobile/tablet: disembunyikan di sini —
             diakses lewat akordion #recent-acc di dalam feed (bukan drawer lagi). --}}
        @php($namaKomunitas = collect($daftarKomunitas)->pluck('nama', 'slug'))
        <aside class="komunitas-aside hidden xl:block">
            <div class="tsaqib-card p-3">
                <p class="px-1 mb-2 text-[10px] font-bold uppercase tracking-wider text-white/40 flex items-center gap-1.5">
                    <i class="fa-solid fa-bolt text-[var(--gold)]"></i> Postingan Terbaru
                </p>

                @if($recentPosts->isNotEmpty())
                    <div class="space-y-0.5">
                        @foreach($recentPosts as $rp)
                            @php($rpThumb = $rp->media->first())
                            <a href="{{ route('komunitas.post.show', $rp->id) }}" class="recent-row group">
                                @if($rpThumb && $rpThumb->type === 'image')
                                    <img src="{{ $rpThumb->url }}" alt="" class="recent-thumb" style="object-fit:cover;">
                                @else
                                    <span class="recent-thumb"><i class="fa-solid fa-image text-sm"></i></span>
                                @endif
                                <div class="min-w-0 flex-1">
                                    <span class="block text-[10px] font-bold uppercase tracking-wide text-[var(--gold)] truncate">{{ $namaKomunitas[$rp->community_slug] ?? $rp->community_slug }}</span>
                                    <p class="text-xs font-semibold text-white/80 group-hover:text-[var(--cream)] line-clamp-1 leading-snug">{{ $rp->title }}</p>
                                    <span class="flex items-center gap-2 text-[10px] text-white/40 mt-0.5">
                                        <span><i class="fa-regular fa-clock mr-0.5"></i>{{ $rp->created_at->diffForHumans() }}</span>
                                        @if($rp->comments_count > 0)
                                            <span><i class="fa-regular fa-comment mr-0.5"></i>{{ $rp->comments_count }}</span>
                                        @endif
                                        <span><i class="fa-solid fa-thumbs-up mr-0.5"></i>{{ $rp->upvotes }}</span>
                                    </span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                    <a href="{{ route('komunitas', 'semua') }}" class="block text-center text-[11px] font-bold text-[var(--gold)] hover:underline mt-2 pt-2 border-t border-white/10">
                        Lihat Semua <i class="fa-solid fa-arrow-right text-[9px]"></i>
                    </a>
                @else
                    <p class="px-1 py-4 text-[11px] text-white/40 text-center">Belum ada postingan.</p>
                @endif
            </div>
        </aside>

        </div>{{-- /komunitas-shell --}}

    </main>

    {{-- ============ SLIDE-IN PANEL "Postingan Terbaru" (mobile/tablet, < xl) ============
         Markup identik dengan sidebar kanan, dibungkus panel geser. Backdrop + body
         overflow-hidden + Escape = pola yang sama dengan mobile-drawer partials/navbar. --}}
    <!-- FLOATING ACTION BUTTON (+) -->
    @auth
        <div class="fixed bottom-5 right-5 z-40">
            <button onclick="openCreateModal()"
                    class="kom-fab w-14 h-14 rounded-full bg-[#01795F] hover:bg-[#3F704D] text-white shadow-xl flex items-center justify-center text-2xl font-bold transition-all transform hover:scale-110 focus:outline-none"
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
    <div id="lightbox" onclick="closeLightbox()">
        <div class="lb-stage" onclick="event.stopPropagation()">
            <div id="lb-item"></div>
            <div class="lb-counter" id="lb-counter"></div>
            <button class="lb-btn lb-close" onclick="closeLightbox()" aria-label="Tutup"><i class="fa-solid fa-xmark"></i></button>
            <button class="lb-btn lb-prev" onclick="lbNav(-1)" aria-label="Sebelumnya"><i class="fa-solid fa-chevron-left"></i></button>
            <button class="lb-btn lb-next" onclick="lbNav(1)" aria-label="Berikutnya"><i class="fa-solid fa-chevron-right"></i></button>
        </div>
    </div>

    <!-- Toast (share link) -->
    <div id="toast" class="fixed bottom-6 left-1/2 -translate-x-1/2 z-[90] hidden bg-[#01795F] text-white text-xs font-semibold px-4 py-2.5 rounded-full shadow-lg"></div>

    <!-- Footer -->
    @include('partials.site-footer')

    <!-- SCRIPT: modal, lightbox, voting (AJAX) -->
    <script>
        /* ===== AKORDION "Postingan Terbaru" (mobile/tablet < xl) =====
           Toggle [hidden] pada body + kelas .is-open pada wadah (untuk memutar
           chevron). Tidak mengunci scroll body (inline accordion, bukan drawer).
           Escape menutup jika sedang terbuka. */
        (function () {
            var trigger = document.getElementById('recent-trigger');
            var acc     = document.getElementById('recent-acc');
            var body    = document.getElementById('recent-acc-body');
            if (! trigger || ! acc || ! body) return;

            function toggle(force) {
                var willOpen = typeof force === 'boolean' ? force : body.hasAttribute('hidden');
                if (willOpen) body.removeAttribute('hidden'); else body.setAttribute('hidden', '');
                acc.classList.toggle('is-open', willOpen);
                trigger.setAttribute('aria-expanded', willOpen ? 'true' : 'false');
            }
            trigger.addEventListener('click', function () { toggle(); });
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && acc.classList.contains('is-open')) toggle(false);
            });
        })();

        /* ===== MODAL BUAT / EDIT ===== */
        function openCreateModal() {
            document.getElementById('create-post-modal').classList.remove('hidden');
        }
        function closeCreateModal() {
            const modal = document.getElementById('create-post-modal');
            modal.classList.add('hidden');
            resetMediaIn(modal); // bersihkan pilihan media saat dibatalkan
        }
        function toggleEditModal(id) {
            const el = document.getElementById('edit-modal-' + id);
            if (! el) return;
            const willClose = ! el.classList.contains('hidden');
            el.classList.toggle('hidden');
            if (willClose) resetMediaIn(el);
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
            const preview = drop.querySelector('[data-preview]');
            const countEl = drop.querySelector('[data-count]');
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
    </script>

</body>
</html>
