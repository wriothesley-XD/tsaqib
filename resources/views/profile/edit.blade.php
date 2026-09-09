<?php $pageTitle = 'Profil - TSAQIB SMAN 1 Bukittinggi'; ?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    @include('partials.theme-head')

    {{-- CSS profil dari file statis (lihat catatan sebelumnya: @push yatim di sini). --}}
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
</head>
<body class="text-[var(--cream)] font-sans antialiased min-h-screen flex flex-col">

    @include('partials.navbar')

    @php
        $authUser = Auth::user();
        $user = $user ?? $authUser;
        $isOwner = $isOwner ?? ((bool) $authUser && $authUser->id === $user->id);
        $isAdmin = ($user->role ?? null) === 'admin';
        $roleLabel = $isAdmin ? 'Admin' : 'Anggota';
        $roleIcon = $isAdmin ? 'fa-shield-halved' : 'fa-user-check';

        // Nama komunitas dari selected_community — SAMA sumber data dengan
        // /komunitas/{slug} (config('komunitas.daftar')). Bukan relasi Eloquent;
        // kolom users.selected_community menyimpan slug.
        $komunitas = collect(\Illuminate\Support\Facades\Config::get('komunitas.daftar', []))
            ->firstWhere('slug', $user->selected_community);
        $komunitasNama = $komunitas['nama'] ?? '';

        $followerCount  = $followerCount ?? 0;
        $followingCount = $followingCount ?? 0;
        $isFollowing    = $isFollowing ?? false;
        $postsCount     = $postsCount ?? 0;
        $commentsCount  = $commentsCount ?? 0;
        $savedCount     = $savedCount ?? 0;
        $booksCount     = $booksCount ?? 0;
    @endphp

    @if(session('status'))
        <script>window.__prFlash = {{ json_encode((string) session('status')) }};</script>
    @endif
    @if($errors->has('password'))
        <script>window.__prDelErr = true;</script>
    @endif

    <main class="flex-1 w-full">

    {{-- ########################################################################
         DESKTOP LAYOUT (≥768px)
         ######################################################################## --}}
    <div class="hidden md:block py-8 px-4">

        {{-- Profile card: SATU kolom terpusat (banner + identitas + tabs), bukan grid dua kolom.
             .pr-card sendiri sudah center + max-width:960px (lihat public/css/profile.css). --}}
        <div class="pr-card">
            <div class="pr-banner2">
                @if($user->banner_path)
                    <img data-banner-img src="{{ asset('storage/'.$user->banner_path) }}" alt="" class="pr-banner-img">
                @endif
            </div>

            <div class="flex flex-col items-center -mt-[70px] px-6">
                <img data-avatar-img src="{{ $user->getAvatar() }}" alt="{{ $user->name }}" class="pr-avatar">

                <h1 class="pr-display font-extrabold text-3xl text-[var(--cream)] mt-3">{{ $user->name }}</h1>

                <div class="mt-2.5 flex items-center justify-center gap-2 flex-wrap">
                    @if($user->is_verified_student)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#01795F]/25 border border-[#01795F]/50 text-[#3fd6b0] text-xs font-bold" title="Siswa Terverifikasi SMAN 1 Bukittinggi">
                            <i class="fa-solid fa-circle-check text-xs"></i> Siswa SMAN 1
                        </span>
                    @endif
                    <span class="pr-role-pill"><i class="fa-solid {{ $roleIcon }} text-[10px]"></i> {{ $roleLabel }}</span>
                    @if($komunitasNama)
                        <a href="{{ route('komunitas', $user->selected_community) }}"
                           class="pr-role-pill pr-role-pill--community"
                           title="Komunitas {{ $komunitasNama }}">
                            <i class="fa-solid fa-users text-[10px]"></i> {{ $komunitasNama }}
                        </a>
                    @endif
                </div>

                <p class="text-xs text-white/50 mt-2.5">
                    <button type="button" class="pr-stat-link" data-people="followers"><strong>{{ $followerCount }}</strong> Pengikut</button>
                    <span class="text-white/20 mx-1">·</span>
                    <button type="button" class="pr-stat-link" data-people="following"><strong>{{ $followingCount }}</strong> Mengikuti</button>
                </p>

                <div class="mt-4 flex items-center gap-2.5">
                    @if($isOwner)
                        <button type="button" onclick="openModal('pr-edit-modal')"
                                class="px-5 py-2 rounded-full bg-gradient-to-r from-[var(--emerald)] to-[var(--green)] text-white font-bold text-sm hover:-translate-y-0.5 transition shadow-[0_8px_22px_-8px_rgba(1,121,95,0.55)]">
                            <i class="fa-solid fa-pen mr-1.5"></i> Edit Profil
                        </button>
                        <form method="POST" action="{{ route('logout') }}">@csrf
                            <button type="submit" class="px-5 py-2 rounded-full border border-white/20 text-white/60 font-semibold text-sm hover:bg-white/5 transition">Log Out</button>
                        </form>
                        <button type="button" onclick="openModal('pr-delete-modal')"
                                class="px-5 py-2 rounded-full border border-red-400/40 text-red-300/90 font-semibold text-sm hover:bg-red-500/15 hover:border-red-400/80 transition">
                            <i class="fa-solid fa-trash-can mr-1.5"></i> Hapus Akun
                        </button>
                    @elseif(Auth::check())
                        <button type="button" id="pr-follow-btn"
                                data-following="{{ $isFollowing ? '1' : '0' }}"
                                data-url-follow="{{ route('profile.follow', $user->id) }}"
                                data-url-unfollow="{{ route('profile.unfollow', $user->id) }}"
                                class="pr-follow-btn px-5 py-2 {{ $isFollowing ? 'is-following' : '' }}">
                            <i class="fa-solid {{ $isFollowing ? 'fa-user-check' : 'fa-user-plus' }}"></i>
                            <span>{{ $isFollowing ? 'Mengikuti' : 'Ikuti' }}</span>
                        </button>
                    @else
                        {{-- Tamu (guest) tidak bisa mengikuti → ajak login. --}}
                        <a href="{{ route('login') }}"
                           class="inline-flex items-center gap-1.5 px-5 py-2 rounded-full bg-gradient-to-r from-[var(--emerald)] to-[var(--green)] text-white font-bold text-sm hover:-translate-y-0.5 transition shadow-[0_8px_22px_-8px_rgba(1,121,95,0.55)]">
                            <i class="fa-solid fa-right-to-bracket"></i>
                            <span>Masuk untuk mengikuti</span>
                        </a>
                    @endif
                </div>
            </div>

            {{-- Tabs + content (tetap di dalam kartu yang sama, di bawah identitas) --}}
            <div class="px-8">
                <div class="pr-dtabs mt-7">
                    <button class="pr-dtab is-active" data-dtab="posts">Postingan</button>
                    <button class="pr-dtab" data-dtab="comments">Komentar</button>
                    @if($isOwner)
                        <button class="pr-dtab" data-dtab="saved">Tersimpan</button>
                    @endif
                    <button class="pr-dtab" data-dtab="books">Buku</button>
                </div>

                <div class="py-6">
                    <div data-dpanel="posts">
                        @include('profile._scroll-activity', [
            'tab' => 'posts',
            'items' => $postsBatch ?? [], 'total' => $postsTotal ?? 0,
            'seeAll' => route('profile.list', [$user->id, 'posts']),
            'emptyIcon' => 'fa-newspaper', 'emptyText' => 'Belum ada postingan.',
                        ])
                    </div>
                    <div data-dpanel="comments" class="hidden">
                        @include('profile._scroll-activity', [
            'tab' => 'comments',
            'items' => $commentsBatch ?? [], 'total' => $commentsTotal ?? 0,
            'seeAll' => route('profile.list', [$user->id, 'comments']),
            'emptyIcon' => 'fa-comment', 'emptyText' => 'Belum ada komentar.',
                        ])
                    </div>
                    @if($isOwner)
                        <div data-dpanel="saved" class="hidden">
                            @include('profile._scroll-activity', [
            'tab' => 'saved',
            'items' => $savedBatch ?? [], 'total' => $savedTotal ?? 0,
            'seeAll' => route('profile.list', [$user->id, 'saved']),
            'emptyIcon' => 'fa-bookmark', 'emptyText' => 'Belum ada postingan tersimpan.',
                            ])
                        </div>
                    @endif
                    {{-- Buku = PUBLIK (boleh dilihat non-owner). Tersimpan tetap owner-only di atas. --}}
                    <div data-dpanel="books" class="hidden">
                        @include('profile._books', ['booksGrouped' => $booksGrouped ?? collect()])
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ########################################################################
         MOBILE LAYOUT (<768px) — struktur berbeda, bukan desktop yang disempitkan
         ######################################################################## --}}
    <div class="md:hidden px-4 py-5">

        {{-- Compact identity header (minimalis: banner + avatar lebih kecil) --}}
        <div class="pr-card">
            <div class="pr-banner2" style="height:96px">
                @if($user->banner_path)
                    <img data-banner-img src="{{ asset('storage/'.$user->banner_path) }}" alt="" class="pr-banner-img" style="height:96px">
                @endif
            </div>
            <div class="flex flex-col items-center -mt-[40px] px-4 pb-5">
                    <img data-avatar-img src="{{ $user->getAvatar() }}" alt="{{ $user->name }}" class="pr-avatar" style="width:80px;height:80px;border-width:3px">
                <h1 class="pr-display font-extrabold text-xl text-[var(--cream)] mt-2">{{ $user->name }}</h1>

                @if(!empty($user->bio))
                    <p class="text-[12px] text-white/55 mt-1.5 text-center leading-snug line-clamp-2 px-2">{{ $user->bio }}</p>
                @endif

                <div class="mt-1.5 flex items-center justify-center gap-1.5 flex-wrap">
                    @if($user->is_verified_student)
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-[#01795F]/25 border border-[#01795F]/50 text-[#3fd6b0] text-[10px] font-bold" title="Siswa Terverifikasi SMAN 1 Bukittinggi">
                            <i class="fa-solid fa-circle-check text-[9px]"></i> Siswa SMAN 1
                        </span>
                    @endif
                    <span class="pr-role-pill text-[10px]"><i class="fa-solid {{ $roleIcon }} text-[9px]"></i> {{ $roleLabel }}</span>
                    @if($komunitasNama)
                        <a href="{{ route('komunitas', $user->selected_community) }}"
                           class="pr-role-pill pr-role-pill--community text-[10px]"
                           title="Komunitas {{ $komunitasNama }}">
                            <i class="fa-solid fa-users text-[9px]"></i> {{ $komunitasNama }}
                        </a>
                    @endif
                </div>
                <p class="text-[11px] text-white/50 mt-1.5">
                    <button type="button" class="pr-stat-link" data-people="followers"><strong>{{ $followerCount }}</strong> Pengikut</button>
                    <span class="text-white/20 mx-0.5">·</span>
                    <button type="button" class="pr-stat-link" data-people="following"><strong>{{ $followingCount }}</strong> Mengikuti</button>
                </p>
                <div class="mt-3 flex items-center gap-2">
                    @if($isOwner)
                        {{-- Cukup 2 tombol aksi di mobile: Edit & Log Out.
                             Hapus Akun tersedia via "Zona Berbahaya" di dalam modal Edit Profil. --}}
                        <button type="button" onclick="openModal('pr-edit-modal')" class="px-4 py-1.5 rounded-full bg-gradient-to-r from-[var(--emerald)] to-[var(--green)] text-white font-bold text-xs">Edit Profil</button>
                        <form method="POST" action="{{ route('logout') }}">@csrf
                            <button type="submit" class="px-4 py-1.5 rounded-full border border-white/20 text-white/60 font-semibold text-xs">Log Out</button>
                        </form>
                    @elseif(Auth::check())
                        <button type="button" id="pr-follow-btn"
                                data-following="{{ $isFollowing ? '1' : '0' }}"
                                data-url-follow="{{ route('profile.follow', $user->id) }}"
                                data-url-unfollow="{{ route('profile.unfollow', $user->id) }}"
                                class="pr-follow-btn px-4 py-1.5 text-xs {{ $isFollowing ? 'is-following' : '' }}">
                            <i class="fa-solid {{ $isFollowing ? 'fa-user-check' : 'fa-user-plus' }}"></i>
                            <span>{{ $isFollowing ? 'Mengikuti' : 'Ikuti' }}</span>
                        </button>
                    @else
                        {{-- Tamu (guest) tidak bisa mengikuti → ajak login. --}}
                        <a href="{{ route('login') }}"
                           class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full bg-gradient-to-r from-[var(--emerald)] to-[var(--green)] text-white font-bold text-xs">
                            <i class="fa-solid fa-right-to-bracket"></i>
                            <span>Masuk untuk mengikuti</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- Tab bar slim (reuse .pr-tabs/.pr-tab) — pengganti grid 2x2 kartu statistik --}}
        <div class="pr-tabs mt-4">
            <button class="pr-tab is-active" data-mtab="posts">Postingan <span class="count">{{ $postsCount }}</span></button>
            <button class="pr-tab" data-mtab="comments">Komentar <span class="count">{{ $commentsCount }}</span></button>
            @if($isOwner)
                <button class="pr-tab" data-mtab="saved">Tersimpan <span class="count">{{ $savedCount }}</span></button>
            @endif
            <button class="pr-tab" data-mtab="books">Buku <span class="count">{{ $booksCount }}</span></button>
        </div>

        {{-- Konten tab (tanpa header "Aktivitas Terbaru" terpisah — tiap panel
             sudah punya "Lihat Semua →"-nya sendiri via _activity). --}}
        <div class="mt-4">
            <div data-mpanel="posts">
                @include('profile._activity', [
                    'items' => $postsBatch ?? [], 'total' => $postsTotal ?? 0,
                    'seeAll' => route('profile.list', [$user->id, 'posts']),
                    'emptyIcon' => 'fa-newspaper', 'emptyText' => 'Belum ada postingan.',
                ])
            </div>
            <div data-mpanel="comments" hidden>
                @include('profile._activity', [
                    'items' => $commentsBatch ?? [], 'total' => $commentsTotal ?? 0,
                    'seeAll' => route('profile.list', [$user->id, 'comments']),
                    'emptyIcon' => 'fa-comment', 'emptyText' => 'Belum ada komentar.',
                ])
            </div>
            @if($isOwner)
                <div data-mpanel="saved" hidden>
                    @include('profile._activity', [
                        'items' => $savedBatch ?? [], 'total' => $savedTotal ?? 0,
                        'seeAll' => route('profile.list', [$user->id, 'saved']),
                        'emptyIcon' => 'fa-bookmark', 'emptyText' => 'Belum ada postingan tersimpan.',
                    ])
                </div>
            @endif
            {{-- Buku = PUBLIK (boleh dilihat non-owner). Tersimpan tetap owner-only di atas. --}}
            <div data-mpanel="books" hidden>
                @include('profile._books', ['booksGrouped' => $booksGrouped ?? collect()])
            </div>
        </div>
    </div>

    </main>

    {{-- ============ MODAL: EDIT PROFIL ============ --}}
    @if($isOwner)
    <div class="pr-modal" id="pr-edit-modal" role="dialog" aria-modal="true" aria-labelledby="pr-edit-title">
        <div class="pr-modal-bg" onclick="closeModal('pr-edit-modal')"></div>
        <div class="pr-modal-card pr-modal-card--form">
            <div class="flex items-center justify-between px-5 py-4 shrink-0 border-b border-white/[0.08]">
                <h3 id="pr-edit-title" class="pr-display font-bold text-lg text-[var(--cream)]">Edit Profil</h3>
                <button type="button" class="pr-x-btn" onclick="closeModal('pr-edit-modal')" aria-label="Tutup"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form id="pr-edit-form" method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="flex flex-col flex-1 min-h-0">
                @csrf @method('PATCH')
                <div class="px-5 py-4 overflow-y-auto flex-1">
                    {{-- Banner (4:1) — divalidasi & di-crop ke 4:1 via canvas sebelum upload --}}
                    <div class="mb-5">
                        <label class="pr-field-label">Banner</label>
                        <div class="pr-banner-drop rounded-xl overflow-hidden border border-white/10 {{ $user->banner_path ? 'has-img' : '' }}" id="pr-banner-drop" style="aspect-ratio:4/1; max-height:150px;" title="Seret & lepas banner ke sini">
                            <img id="pr-banner-preview" src="{{ $user->banner_path ? asset('storage/'.$user->banner_path) : '' }}" alt="Pratinjau banner" class="pr-banner-preview-img">
                            <div id="pr-banner-placeholder" class="pr-banner-placeholder"><i class="fa-solid fa-image"></i><span>Pratinjau banner (4:1)</span></div>
                        </div>
                        <div class="flex items-center gap-3 mt-2 flex-wrap">
                            <label class="pr-upload-btn">
                                <i class="fa-solid fa-image"></i> Ganti Banner
                                <input type="file" name="banner" id="pr-banner-input" accept="image/jpeg,image/png,image/webp" hidden>
                            </label>
                            <p class="text-[10px] text-white/40">Rekomendasi 1200×300px (rasio 4:1), maks 2MB. Disesuaikan otomatis ke 4:1.</p>
                        </div>
                        <p class="pr-err hidden" id="pr-err-banner" data-err="banner"></p>
                    </div>

                    <div class="flex items-center gap-4 mb-5">
                        <div class="pr-upload-ring" id="pr-avatar-drop" title="Seret & lepas foto ke sini">
                            <img id="pr-avatar-preview" src="{{ $user->getAvatar() }}" alt="Pratinjau avatar">
                        </div>
                        <div class="min-w-0">
                            <label class="pr-upload-btn">
                                <i class="fa-solid fa-camera"></i> Ganti Foto
                                <input type="file" name="avatar" id="pr-avatar-input" accept="image/jpeg,image/png,image/webp" hidden>
                            </label>
                            <p class="text-[10px] text-white/40 mt-1.5">JPG / PNG / WEBP, maks 2MB. Bisa seret &amp; lepas.</p>
                            <p class="text-[11px] text-[var(--gold)] mt-1 truncate hidden" id="pr-avatar-name"></p>
                            @if($user->profile_photo_path || $user->avatar)
                            <button type="button" id="pr-remove-avatar" data-url="{{ route('profile.avatar.destroy') }}"
                                    class="mt-2 inline-flex items-center gap-1.5 text-[11px] font-semibold text-red-300/80 hover:text-red-300 transition-colors">
                                <i class="fa-solid fa-trash-can"></i> Hapus Foto Profil
                            </button>
                            @endif
                        </div>
                    </div>
                    <p class="pr-err hidden" id="pr-err-avatar" data-err="avatar"></p>

                    {{-- Avatar bawaan (preset) — pre-select avatar saat ini --}}
                    <div class="mb-5">
                        <span class="pr-field-label">atau pilih avatar bawaan</span>
                        <div class="mt-1.5">
                            <x-avatar-picker name="preset_avatar" :selected="$user->avatar" />
                        </div>
                    </div>

                    <div class="pr-field">
                        <label class="pr-field-label" for="pr-name">Nama</label>
                        <input type="text" name="name" id="pr-name" value="{{ old('name', $user->name) }}" class="tsaqib-input w-full px-3 py-2.5 text-sm" required>
                        <p class="pr-err hidden" id="pr-err-name" data-err="name"></p>
                    </div>
                    <div class="pr-field">
                        <label class="pr-field-label" for="pr-email">Email</label>
                        <input type="email" name="email" id="pr-email" value="{{ old('email', $user->email) }}" class="tsaqib-input w-full px-3 py-2.5 text-sm" required>
                        <p class="pr-err hidden" id="pr-err-email" data-err="email"></p>
                    </div>
                    <div class="pr-field">
                        <div class="flex items-center justify-between">
                            <label class="pr-field-label" for="pr-bio">Bio</label>
                            <span class="text-[10px]" id="pr-bio-count">{{ mb_strlen(old('bio', $user->bio ?? '')) }}/160</span>
                        </div>
                        <textarea name="bio" id="pr-bio" rows="3" maxlength="160" class="tsaqib-input w-full px-3 py-2.5 text-sm resize-none" placeholder="Ceritakan sedikit tentang Anda...">{{ old('bio', $user->bio ?? '') }}</textarea>
                        <p class="pr-err hidden" id="pr-err-bio" data-err="bio"></p>
                    </div>

                    {{-- Ganti Komunitas — daftar SAMA dengan /komunitas/{slug} & select-role
                         (config('komunitas.daftar')). Pre-select komunitas user saat ini. --}}
                    <div class="mb-5">
                        <span class="pr-field-label">Ganti Komunitas</span>
                        <div class="mt-1.5">
                            <x-community-picker name="community_slug" :selected="old('community_slug', $user->selected_community)" />
                        </div>
                        <p class="pr-err hidden" id="pr-err-community_slug" data-err="community_slug"></p>
                    </div>

                    {{-- Zona Berbahaya: pintu masuk Hapus Akun dari dalam modal Edit Profil
                         (menggantikan tombol Hapus di tampilan mobile). Membuka pr-delete-modal. --}}
                    <div class="mb-1 rounded-xl border border-red-400/25 bg-red-500/[0.05] p-3">
                        <span class="pr-field-label text-red-300/80">Zona Berbahaya</span>
                        <button type="button" onclick="openModal('pr-delete-modal')"
                                class="mt-2 w-full inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg border border-red-400/40 text-red-300/90 font-semibold text-xs hover:bg-red-500/15 hover:border-red-400/80 transition">
                            <i class="fa-solid fa-user-xmark"></i> Hapus Akun
                        </button>
                    </div>
                </div>
                <div class="flex gap-2 px-5 py-4 shrink-0 border-t border-white/[0.08]">
                    <button type="button" class="pr-ghost-btn flex-1 justify-center" onclick="closeModal('pr-edit-modal')">Batal</button>
                    <button type="submit" class="pr-save-btn flex-1" id="pr-edit-submit"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL: HAPUS AKUN --}}
    <div class="pr-modal" id="pr-delete-modal" role="dialog" aria-modal="true" aria-labelledby="pr-delete-title">
        <div class="pr-modal-bg" onclick="closeModal('pr-delete-modal')"></div>
        <div class="pr-modal-card">
            <div class="flex items-center gap-3 mb-3">
                <span class="pr-danger-ic"><i class="fa-solid fa-user-xmark"></i></span>
                <h3 id="pr-delete-title" class="pr-display font-bold text-lg text-[#fca5a5]">Hapus Akun?</h3>
            </div>
            <p class="text-xs text-white/60 mb-4">Masukkan password untuk konfirmasi. Tindakan ini <strong class="text-[#fca5a5]">tidak dapat dibatalkan</strong> dan semua data Anda akan dihapus permanen.</p>
            <form method="POST" action="{{ route('profile.destroy') }}" id="pr-delete-form">
                @csrf @method('DELETE')
                <label class="pr-field-label" for="pr-delete-pw">Password</label>
                <div class="relative">
                    <input type="password" name="password" id="pr-delete-pw" placeholder="Masukkan password Anda" class="tsaqib-input w-full px-3 py-2.5 text-sm pr-10" autocomplete="current-password">
                    <button type="button" class="pr-pw-toggle" data-target="pr-delete-pw" tabindex="-1" aria-label="Tampilkan password"><i class="fa-regular fa-eye"></i></button>
                </div>
                @error('password')<p class="pr-err">{{ $message }}</p>@enderror
                <button type="submit" id="pr-delete-submit" class="pr-danger-confirm-btn w-full mt-4" disabled><i class="fa-solid fa-trash"></i> Hapus Permanen</button>
            </form>
        </div>
    </div>

    {{-- MODAL: KONFIRMASI HAPUS POSTINGAN --}}
    <div class="pr-modal" id="pr-confirm-modal" role="dialog" aria-modal="true">
        <div class="pr-modal-bg" onclick="closeModal('pr-confirm-modal')"></div>
        <div class="pr-modal-card">
            <div class="flex items-center gap-3 mb-2">
                <span class="pr-confirm-ic"><i class="fa-solid fa-triangle-exclamation"></i></span>
                <h3 class="pr-display font-bold text-lg text-[var(--cream)]" id="pr-confirm-title">Hapus?</h3>
            </div>
            <p class="text-xs text-white/60 mb-4" id="pr-confirm-body">Tindakan ini tidak dapat dibatalkan.</p>
            <div class="flex gap-2">
                <button type="button" class="pr-ghost-btn flex-1 justify-center" onclick="closeModal('pr-confirm-modal')">Batal</button>
                <button type="button" class="pr-danger-confirm-btn flex-1" id="pr-confirm-ok"><i class="fa-solid fa-trash"></i> Hapus</button>
            </div>
        </div>
    </div>
    @endif

    {{-- MODAL: PENGIKUT / MENIKUTI (semua orang) --}}
    <div class="pr-modal" id="pr-people-modal" role="dialog" aria-modal="true" aria-labelledby="pr-people-title"
         data-url-followers="{{ route('profile.followers', $user->id) }}"
         data-url-following="{{ route('profile.following', $user->id) }}">
        <div class="pr-modal-bg" onclick="closeModal('pr-people-modal')"></div>
        <div class="pr-modal-card">
            <div class="flex items-center justify-between mb-3">
                <h3 id="pr-people-title" class="pr-display font-bold text-lg text-[var(--cream)]">Pengikut</h3>
                <button type="button" class="pr-x-btn" onclick="closeModal('pr-people-modal')" aria-label="Tutup"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="pr-tabs mb-3">
                <button class="pr-tab is-active" data-people-tab="followers">Pengikut</button>
                <button class="pr-tab" data-people-tab="following">Mengikuti</button>
            </div>
            <div id="pr-people-list" class="space-y-1 max-h-[50vh] overflow-y-auto">
                <div id="pr-people-sentinel" class="pr-sentinel"></div>
            </div>
        </div>
    </div>

    <div class="pr-toasts" id="pr-toasts" aria-live="polite"></div>

    @include('partials.site-footer')

    <script>
    (function () {
        var csrf = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').content : '';

        /* ---------- Modal helpers ---------- */
        window.openModal = function (id) { var m = document.getElementById(id); if (m) { m.classList.add('is-open'); document.body.style.overflow = 'hidden'; } };
        window.closeModal = function (id) { var m = document.getElementById(id); if (m) { m.classList.remove('is-open'); document.body.style.overflow = ''; } };
        document.addEventListener('keydown', function (e) { if (e.key === 'Escape') { document.querySelectorAll('.pr-modal.is-open').forEach(function (m){ m.classList.remove('is-open'); }); document.body.style.overflow = ''; } });

        /* ---------- Toast ---------- */
        function toast(msg, type){
            type = type || 'success';
            var wrap = document.getElementById('pr-toasts');
            var el = document.createElement('div');
            el.className = 'pr-toast pr-toast-' + type;
            var ic = type === 'success' ? 'fa-circle-check' : 'fa-circle-exclamation';
            el.innerHTML = '<i class="fa-solid ' + ic + '"></i><span></span>';
            el.querySelector('span').textContent = msg;
            wrap.appendChild(el);
            requestAnimationFrame(function (){ el.classList.add('show'); });
            setTimeout(function (){ el.classList.remove('show'); setTimeout(function (){ if (el.parentNode) el.parentNode.removeChild(el); }, 300); }, 3600);
        }
        window.__prToast = toast;

        function escapeHtml(s){ return String(s==null?'':s).replace(/[&<>"']/g, function(c){ return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]; }); }

        /* ---------- Bio counter ---------- */
        var bio = document.getElementById('pr-bio');
        var bioCount = document.getElementById('pr-bio-count');
        function syncBio(){ if (!bio || !bioCount) return; var n = bio.value.length; bioCount.textContent = n + '/160'; bioCount.classList.toggle('is-warn', n > 140); }
        if (bio) { bio.addEventListener('input', syncBio); syncBio(); }

        /* ---------- Avatar upload + drag-and-drop ---------- */
        var avatarInput = document.getElementById('pr-avatar-input');
        var avatarPreview = document.getElementById('pr-avatar-preview');
        var avatarName = document.getElementById('pr-avatar-name');
        var avatarDrop = document.getElementById('pr-avatar-drop');
        function handleAvatarFile(f){
            if (!f) return;
            if (!/^image\/(jpeg|png|webp)$/.test(f.type)) { toast('Format foto harus JPG/PNG/WEBP.', 'error'); if (avatarInput) avatarInput.value = ''; return; }
            if (f.size > 2 * 1024 * 1024) { toast('Ukuran foto melebihi 2MB.', 'error'); if (avatarInput) avatarInput.value = ''; return; }
            // Preset picker: kosongkan pilihan agar upload (foto) diutamakan.
            var pickerActive = document.querySelector('#pr-edit-form .av-pick.is-active');
            if (pickerActive) pickerActive.classList.remove('is-active');
            var pickerInput = document.querySelector('#pr-edit-form [data-avatar-input]');
            if (pickerInput) pickerInput.value = '';

            var reader = new FileReader();
            reader.onload = function (e){ if (avatarPreview) avatarPreview.src = e.target.result; };
            reader.readAsDataURL(f);
            if (avatarName) { avatarName.textContent = f.name; avatarName.classList.remove('hidden'); }
            try { var dt = new DataTransfer(); dt.items.add(f); if (avatarInput) avatarInput.files = dt.files; } catch (e2) {}
        }
        if (avatarInput) avatarInput.addEventListener('change', function (){ handleAvatarFile(this.files && this.files[0]); });
        if (avatarDrop) {
            avatarDrop.addEventListener('dragover', function (e){ e.preventDefault(); avatarDrop.classList.add('pr-upload-ring--over'); });
            avatarDrop.addEventListener('dragleave', function (){ avatarDrop.classList.remove('pr-upload-ring--over'); });
            avatarDrop.addEventListener('drop', function (e){ e.preventDefault(); avatarDrop.classList.remove('pr-upload-ring--over'); handleAvatarFile(e.dataTransfer && e.dataTransfer.files && e.dataTransfer.files[0]); });
        }

        /* ---------- Preset picker → sinkron pratinjau ring & bersihkan upload ---------- */
        var editFormScope = document.getElementById('pr-edit-form');
        if (editFormScope) {
            editFormScope.addEventListener('avatar:selected', function (e){
                var d = e.detail || {};
                if (avatarPreview && d.img) avatarPreview.src = d.img;       // tampilkan preset di ring
                if (avatarInput) { try { avatarInput.value = ''; } catch (e2){} } // hapus pilihan file upload
                if (avatarName) avatarName.classList.add('hidden');
            });
        }

        /* ---------- Hapus foto profil (reset ke default) ---------- */
        var rmAvatarBtn = document.getElementById('pr-remove-avatar');
        if (rmAvatarBtn) {
            rmAvatarBtn.addEventListener('click', function (){
                var orig = rmAvatarBtn.innerHTML;
                rmAvatarBtn.disabled = true;
                rmAvatarBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Mereset...';
                fetch(rmAvatarBtn.dataset.url, { method:'DELETE', headers:{ 'X-CSRF-TOKEN':csrf, 'Accept':'application/json' }, credentials:'same-origin' })
                    .then(function (r){ return r.json().catch(function(){ return {}; }).then(function(j){ return { ok:r.ok, json:j }; }); })
                    .then(function (res){
                        if (res.ok) {
                            // pratinjau ring + kartu profil (desktop & mobile) → avatar default
                            if (avatarPreview && res.json && res.json.avatar) avatarPreview.src = res.json.avatar;
                            document.querySelectorAll('[data-avatar-img]').forEach(function (el){ el.src = res.json.avatar; });
                            // avatar kini default → kosongkan picker & file input
                            var pa = document.querySelector('#pr-edit-form .av-pick.is-active'); if (pa) pa.classList.remove('is-active');
                            var pi = document.querySelector('#pr-edit-form [data-avatar-input]'); if (pi) pi.value = '';
                            if (avatarInput) { try { avatarInput.value = ''; } catch (e2){} }
                            if (avatarName) avatarName.classList.add('hidden');
                            toast((res.json && res.json.message) || 'Foto profil direset.', 'success');
                        } else {
                            toast((res.json && res.json.message) || 'Gagal mereset foto.', 'error');
                        }
                    })
                    .catch(function (){ toast('Gagal menyambung ke server.', 'error'); })
                    .finally(function (){ rmAvatarBtn.disabled = false; rmAvatarBtn.innerHTML = orig; });
            });
        }

        /* ---------- Banner upload + crop otomatis 4:1 via canvas ----------
           Center-crop ke rasio 4:1 (tidak meregang), cap lebar 1200px, lalu
           re-encode ke JPEG dan masukkan ke <input name="banner"> agar server
           menerima gambar yang sudah 4:1. */
        var bannerInput = document.getElementById('pr-banner-input');
        var bannerDrop = document.getElementById('pr-banner-drop');
        var bannerPreview = document.getElementById('pr-banner-preview');
        function handleBannerFile(f){
            if (!f) return;
            if (!/^image\/(jpeg|png|webp)$/.test(f.type)) { toast('Format banner harus JPG/PNG/WEBP.', 'error'); if (bannerInput) bannerInput.value = ''; return; }
            if (f.size > 2 * 1024 * 1024) { toast('Ukuran banner melebihi 2MB.', 'error'); if (bannerInput) bannerInput.value = ''; return; }
            var reader = new FileReader();
            reader.onload = function (e){
                var img = new Image();
                img.onload = function (){
                    var sw = img.naturalWidth, sh = img.naturalHeight, ratio = 4;
                    var cw, ch, sx, sy;
                    if (sw / sh > ratio) { ch = sh; cw = sh * ratio; sx = (sw - cw) / 2; sy = 0; }   // sumber terlalu lebar → potong kiri/kanan
                    else { cw = sw; ch = sw / ratio; sx = 0; sy = (sh - ch) / 2; }                    // sumber terlalu tinggi → potong atas/bawah
                    var outW = Math.round(Math.min(cw, 1200)), outH = Math.round(outW / ratio);
                    var c = document.createElement('canvas'); c.width = outW; c.height = outH;
                    c.getContext('2d').drawImage(img, sx, sy, cw, ch, 0, 0, outW, outH);
                    c.toBlob(function (blob){
                        if (!blob) { toast('Gagal memproses banner.', 'error'); return; }
                        if (bannerPreview) bannerPreview.src = URL.createObjectURL(blob);
                        if (bannerDrop) bannerDrop.classList.add('has-img');
                        try { var dt = new DataTransfer(); dt.items.add(new File([blob], 'banner.jpg', { type: 'image/jpeg' })); if (bannerInput) bannerInput.files = dt.files; } catch (e2) {}
                    }, 'image/jpeg', 0.92);
                };
                img.src = e.target.result;
            };
            reader.readAsDataURL(f);
        }
        if (bannerInput) bannerInput.addEventListener('change', function (){ handleBannerFile(this.files && this.files[0]); });
        if (bannerDrop) {
            bannerDrop.addEventListener('dragover', function (e){ e.preventDefault(); bannerDrop.classList.add('pr-upload-ring--over'); });
            bannerDrop.addEventListener('dragleave', function (){ bannerDrop.classList.remove('pr-upload-ring--over'); });
            bannerDrop.addEventListener('drop', function (e){ e.preventDefault(); bannerDrop.classList.remove('pr-upload-ring--over'); handleBannerFile(e.dataTransfer && e.dataTransfer.files && e.dataTransfer.files[0]); });
        }

        /* ---------- Password gate + eye toggle ---------- */
        var delPw = document.getElementById('pr-delete-pw');
        var delBtn = document.getElementById('pr-delete-submit');
        if (delPw && delBtn) delPw.addEventListener('input', function (){ delBtn.disabled = delPw.value.trim().length === 0; });
        document.querySelectorAll('.pr-pw-toggle').forEach(function (btn){
            btn.addEventListener('click', function (){ var inp = document.getElementById(btn.dataset.target); if (!inp) return; var show = inp.type === 'password'; inp.type = show ? 'text' : 'password'; btn.innerHTML = show ? '<i class="fa-regular fa-eye-slash"></i>' : '<i class="fa-regular fa-eye"></i>'; });
        });

        /* ---------- Edit profile form (AJAX) ---------- */
        var editForm = document.getElementById('pr-edit-form');
        if (editForm) {
            var clearErrs = function (){ editForm.querySelectorAll('.pr-err').forEach(function (el){ el.classList.add('hidden'); el.textContent = ''; }); };
            var showErrs = function (errors){ errors = errors || {}; Object.keys(errors).forEach(function (field){ var slot = editForm.querySelector('.pr-err[data-err="' + field + '"]'); if (slot) { slot.textContent = errors[field][0] || errors[field]; slot.classList.remove('hidden'); } }); };
            editForm.addEventListener('submit', function (e){
                e.preventDefault(); clearErrs();
                var btn = document.getElementById('pr-edit-submit'); var orig = btn.innerHTML;
                btn.disabled = true; btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Menyimpan...';
                fetch(editForm.action, { method:'POST', headers:{ 'X-CSRF-TOKEN':csrf, 'Accept':'application/json' }, body:new FormData(editForm), credentials:'same-origin' })
                    .then(function (r){ return r.json().then(function (j){ return { ok:r.ok, json:j }; }); })
                    .then(function (res){
                        if (res.ok) {
                            toast((res.json && res.json.message) || 'Profil berhasil diperbarui.', 'success');
                            // live update banner profil (re-upload); first upload tampil saat reload
                            if (res.json && res.json.banner) {
                                document.querySelectorAll('[data-banner-img]').forEach(function (el){ el.src = res.json.banner; });
                            }
                            // live update avatar profil (preset / upload) tanpa reload
                            if (res.json && res.json.avatar) {
                                document.querySelectorAll('[data-avatar-img]').forEach(function (el){ el.src = res.json.avatar; });
                            }
                            closeModal('pr-edit-modal');
                        } else { showErrs(res.json && res.json.errors ? res.json.errors : {}); toast((res.json && res.json.message) || 'Periksa kembali isian Anda.', 'error'); }
                    })
                    .catch(function (){ toast('Gagal menyambung ke server.', 'error'); })
                    .finally(function (){ btn.disabled = false; btn.innerHTML = orig; });
            });
        }

        /* ---------- Delete post (delegated AJAX via confirm modal) ---------- */
        var confirmTitle = document.getElementById('pr-confirm-title');
        var confirmBody = document.getElementById('pr-confirm-body');
        var confirmOk = document.getElementById('pr-confirm-ok');
        var pendingDelete = null;
        var destroyUrlTpl = "{{ route('posts.destroy', ['__PID__']) }}";
        document.addEventListener('click', function (e){
            var btn = e.target.closest('[data-delete-post]'); if (!btn) return;
            e.preventDefault();
            pendingDelete = { id: btn.dataset.deletePost, item: btn.closest('.pr-item') };
            if (confirmTitle) confirmTitle.textContent = 'Hapus postingan?';
            if (confirmBody) confirmBody.textContent = 'Postingan ini akan dihapus permanen.';
            openModal('pr-confirm-modal');
        });
        if (confirmOk) {
            confirmOk.addEventListener('click', function (){
                if (!pendingDelete) return;
                closeModal('pr-confirm-modal');
                var id = pendingDelete.id, item = pendingDelete.item; pendingDelete = null;
                fetch(destroyUrlTpl.replace('__PID__', id), { method:'POST', headers:{ 'X-CSRF-TOKEN':csrf, 'Accept':'application/json' }, body:new URLSearchParams({ _method:'DELETE' }), credentials:'same-origin' })
                    .then(function (r){ return r.json().catch(function(){ return {}; }).then(function(j){ return { ok:r.ok, json:j }; }); })
                    .then(function (res){ if (res.ok) { if (item) item.remove(); toast('Postingan dihapus.', 'success'); } else { toast('Gagal menghapus.', 'error'); } })
                    .catch(function (){ toast('Gagal menyambung ke server.', 'error'); });
            });
        }

        /* ---------- Follow toggle ---------- */
        var fb = document.getElementById('pr-follow-btn');
        if (fb) {
            fb.addEventListener('click', function (){
                var following = fb.dataset.following === '1';
                var url = following ? fb.dataset.urlUnfollow : fb.dataset.urlFollow;
                fb.disabled = true;
                fetch(url, { method: following ? 'DELETE' : 'POST', headers:{ 'X-CSRF-TOKEN':csrf, 'Accept':'application/json' }, credentials:'same-origin' })
                    .then(function (r){ return r.json(); })
                    .then(function (res){
                        var now = !!res.following;
                        fb.dataset.following = now ? '1' : '0';
                        fb.classList.toggle('is-following', now);
                        fb.innerHTML = now ? '<i class="fa-solid fa-user-check"></i><span>Mengikuti</span>' : '<i class="fa-solid fa-user-plus"></i><span>Ikuti</span>';
                        document.querySelectorAll('[data-follower-count]').forEach(function (el){ el.textContent = res.followerCount; });
                    })
                    .catch(function (){ toast('Gagal mengubah status ikutan.', 'error'); })
                    .finally(function (){ fb.disabled = false; });
            });
        }

        /* ---------- People modal (followers/following, infinite scroll) ---------- */
        (function (){
            var peopleModal = document.getElementById('pr-people-modal'); if (!peopleModal) return;
            var pList = document.getElementById('pr-people-list');
            var pSentinel = document.getElementById('pr-people-sentinel');
            var pTabs = peopleModal.querySelectorAll('.pr-tab');
            var pTitle = document.getElementById('pr-people-title');
            var st = { which:'followers', cursor:'', loading:false, done:false };
            function pStatus(h){ if (pSentinel) pSentinel.innerHTML = h || ''; }
            function pUrl(){ return st.which === 'followers' ? peopleModal.dataset.urlFollowers : peopleModal.dataset.urlFollowing; }
            function pRenderPerson(u){ return '<div class="pr-person"><img src="'+u.avatar+'" alt="" class="pr-person-avatar"><div class="min-w-0 flex-1"><p class="font-bold text-sm text-[var(--cream)] truncate">'+escapeHtml(u.name)+'</p></div><a href="'+u.profile_url+'" class="pr-ghost-btn">Lihat</a></div>'; }
            function pLoad(reset){
                if (st.loading) return;
                if (reset){ st.cursor=''; st.done=false; if (pList) pList.querySelectorAll('.pr-person').forEach(function(n){ n.remove(); }); }
                if (st.done) return;
                st.loading = true; pStatus('<span><i class="fa-solid fa-spinner fa-spin"></i> Memuat…</span>');
                fetch(pUrl() + '?cursor=' + encodeURIComponent(st.cursor), { headers:{ 'Accept':'application/json' }, credentials:'same-origin' })
                    .then(function (r){ return r.json(); })
                    .then(function (res){
                        (res.items||[]).forEach(function (u){ if (pSentinel) pSentinel.insertAdjacentHTML('beforebegin', pRenderPerson(u)); });
                        st.cursor = res.next_cursor || ''; st.loading = false;
                        var hasPeople = !!(pList && pList.querySelector('.pr-person'));
                        if (res.has_more && st.cursor) { pStatus(hasPeople ? '' : '<span>Tidak ada data.</span>'); if (!hasPeople) st.done = true; }
                        else { st.done = true; pStatus(hasPeople ? '<span>Tidak ada lagi.</span>' : '<span>Tidak ada data.</span>'); }
                    })
                    .catch(function (){ st.loading = false; pStatus('<span>Gagal memuat.</span>'); });
            }
            new IntersectionObserver(function (entries){ if (entries[0].isIntersecting) pLoad(false); }, { root:pList, rootMargin:'120px' }).observe(pSentinel);
            pTabs.forEach(function (t){ t.addEventListener('click', function (){ pTabs.forEach(function (x){ x.classList.remove('is-active'); }); t.classList.add('is-active'); st.which = t.dataset.peopleTab; if (pTitle) pTitle.textContent = st.which === 'followers' ? 'Pengikut' : 'Mengikuti'; pLoad(true); }); });
            document.querySelectorAll('[data-people]').forEach(function (btn){ btn.addEventListener('click', function (){ var which = btn.dataset.people; pTabs.forEach(function (t){ t.classList.toggle('is-active', t.dataset.peopleTab === which); }); st.which = which; if (pTitle) pTitle.textContent = which === 'followers' ? 'Pengikut' : 'Mengikuti'; openModal('pr-people-modal'); pLoad(true); }); });

            /* Link profil di dalam modal (follower/following "Lihat") dimuat dinamis,
               jadi pakai delegated listener. TANPA preventDefault — biarkan <a>
               navigasi native. Kita cuma menutup modal lebih dulu agar saat user
               menekan tombol Back, modal tidak tertinggal terbuka di belakang. */
            if (pList) {
                pList.addEventListener('click', function (e){
                    if (e.target.closest('a[href]')) closeModal('pr-people-modal');
                });
            }
        })();

        /* ---------- DESKTOP tabs (Posts / Comments / Books) ---------- */
        (function (){
            var dtabs = document.querySelectorAll('[data-dtab]');
            if (!dtabs.length) return;
            dtabs.forEach(function (t){
                t.addEventListener('click', function (){
                    dtabs.forEach(function (x){ x.classList.remove('is-active'); });
                    document.querySelectorAll('[data-dpanel]').forEach(function (p){ p.classList.add('hidden'); });
                    t.classList.add('is-active');
                    var p = document.querySelector('[data-dpanel="'+t.dataset.dtab+'"]'); if (p) p.classList.remove('hidden');
                });
            });
        })();

        /* ---------- DESKTOP infinite scroll (posts / comments / saved) ----------
           Batch pertama dirender server-side (6); sisanya dimuat bertahap dari
           GET /profile/{user}/tabs/{tab}?cursor= (JSON: items, next_cursor,
           has_more). Pola identik dengan modal Pengikut/Mengikuti: sentinel
           di-observe IntersectionObserver, state per-tab {cursor,loading,done}.

           Dedup berbasis id: batch server = 6, halaman tabs() = 10 → halaman
           pertama bisa tumpang-tindih dgn yg sudah dirender. Id yg sudah ada
           dilewati agar tak dobel. */
        (function () {
            var scrollContainers = document.querySelectorAll('[data-dscroll]');
            if (!scrollContainers.length) return;

            // Markup HARUS cocok dengan profile/_item.blade.php (render server).
            function renderItem(it){
                var iconCell = it.thumb
                    ? '<img src="'+it.thumb+'" alt="" class="w-full h-full object-cover">'
                    : '<i class="fa-solid '+(it.icon||'fa-feather')+'"></i>';
                var eyebrow = it.eyebrow ? '<span class="text-[10px] font-bold text-[var(--gold)] uppercase tracking-wider block">'+escapeHtml(it.eyebrow)+'</span>' : '';
                var excerpt = it.excerpt ? '<p class="text-xs text-white/55 truncate">'+escapeHtml(it.excerpt)+'</p>' : '';
                var del = it.deletable ? '<button type="button" class="pr-item-del" data-delete-post="'+it.id+'" title="Hapus postingan" aria-label="Hapus postingan"><i class="fa-solid fa-trash-can"></i></button>' : '';
                return '<div class="pr-item" data-item-id="'+it.id+'">'
                    + '<div class="pr-item-icon '+(it.iconGold?'pr-item-icon-gold':'')+'">'+iconCell+'</div>'
                    + '<div class="min-w-0 flex-1"><a href="'+it.link+'" class="block min-w-0">'+eyebrow
                    + '<h4 class="font-bold text-sm text-[var(--cream)] truncate">'+escapeHtml(it.title||'')+'</h4>'+excerpt+'</a></div>'
                    + del + '</div>';
            }

            scrollContainers.forEach(function (root){
                var list = root.querySelector('[data-dscroll-list]');
                var sentinel = root.querySelector('[data-dscroll-sentinel]');
                if (!list || !sentinel) return; // empty-state: tak ada sentinel

                var url = root.dataset.url;
                var total = parseInt(root.dataset.total || '0', 10);
                var st = { cursor:'', loading:false, done:false };

                function haveIds(){ var ids={}; list.querySelectorAll('[data-item-id]').forEach(function(n){ ids[n.dataset.itemId]=1; }); return ids; }
                function setStatus(h){ sentinel.innerHTML = h || ''; }
                function reachedEnd(res){
                    // Berhenti bila endpoint bilang tak ada lagi ATAU jumlah item
                    // yg dirender sudah >= total server (jaga-jaga bila has_more tak akurat).
                    var shown = list.querySelectorAll('[data-item-id]').length;
                    return !res.has_more || !st.cursor || shown >= total;
                }
                function load(){
                    if (st.loading || st.done) return;
                    st.loading = true;
                    setStatus('<span><i class="fa-solid fa-spinner fa-spin"></i> Memuat…</span>');
                    fetch(url + '?cursor=' + encodeURIComponent(st.cursor), { headers:{ 'Accept':'application/json' }, credentials:'same-origin' })
                        .then(function (r){ return r.json(); })
                        .then(function (res){
                            var seen = haveIds();
                            (res.items||[]).forEach(function (it){
                                if (seen[it.id]) return;          // dedup vs batch server
                                list.insertAdjacentHTML('beforeend', renderItem(it));
                            });
                            st.cursor = res.next_cursor || '';
                            st.loading = false;
                            var shown = list.querySelectorAll('[data-item-id]').length;
                            if (reachedEnd(res)) { st.done = true; setStatus(shown ? '<span>Tidak ada lagi.</span>' : ''); }
                            else { setStatus(''); }
                        })
                        .catch(function (){ st.loading = false; setStatus('<span>Gagal memuat.</span>'); });
                }

                new IntersectionObserver(function (entries){
                    if (entries[0].isIntersecting) load();
                }, { root:root, rootMargin:'120px' }).observe(sentinel);
            });
        })();

        /* ---------- MOBILE tab grid (2x2) + content switch ---------- */
        (function (){
            var mtabs = document.querySelectorAll('[data-mtab]'); if (!mtabs.length) return;
            var mhead = document.querySelector('[data-mhead]');
            var mseeall = document.querySelector('[data-mseeall]');
            var headLabel = { posts:'AKTIVITAS TERBARU', comments:'AKTIVITAS TERBARU', saved:'AKTIVITAS TERBARU', books:'KOLEKSI BUKU' };
            mtabs.forEach(function (t){
                t.addEventListener('click', function (){
                    mtabs.forEach(function (x){ x.classList.remove('is-active'); });
                    document.querySelectorAll('[data-mpanel]').forEach(function (p){ p.hidden = true; });
                    t.classList.add('is-active');
                    var key = t.dataset.mtab;
                    var p = document.querySelector('[data-mpanel="'+key+'"]'); if (p) p.hidden = false;
                    if (mhead) mhead.textContent = headLabel[key] || 'AKTIVITAS TERBARU';
                    if (mseeall) { var k = key.charAt(0).toUpperCase() + key.slice(1); mseeall.href = mseeall.dataset['url'+k] || '#'; }
                });
            });
        })();

        /* ---------- Flash / delete-error openers ---------- */
        if (window.__prFlash) { toast('Profil berhasil diperbarui.', 'success'); }
        if (window.__prDelErr) { openModal('pr-delete-modal'); toast('Gagal menghapus akun — periksa pesan di form.', 'error'); }
    })();
    </script>

</body>
</html>
