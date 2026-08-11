{{-- resources/views/profile/list.blade.php
    Halaman full-list satu tipe konten profil (tombol "Lihat Semua" di tiap tab).
    Pagination bernomor (partials.pagination). Memakai profile.css + _item partial. --}}
<?php $pageTitle = ($tabTitle ?? 'Daftar') . ' - Profil TSAQIB'; ?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    @include('partials.theme-head')
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
</head>
<body class="text-[var(--cream)] font-sans antialiased min-h-screen flex flex-col">

    @include('partials.navbar')

    <main class="flex-1 max-w-3xl mx-auto px-4 sm:px-6 py-8 sm:py-12 w-full">

        <div class="flex items-center justify-between gap-3 mb-5">
            <div class="min-w-0">
                <a href="{{ route('profile.show', $user->id) }}" class="text-xs text-white/50 hover:text-[var(--gold)] inline-flex items-center gap-1">
                    <i class="fa-solid fa-arrow-left text-[10px]"></i> Kembali ke profil
                </a>
                <h1 class="pr-display font-extrabold text-xl sm:text-2xl text-[var(--cream)] tracking-tight mt-2">
                    {{ $tabTitle }} — {{ $user->name }}
                </h1>
            </div>
            <span class="text-[10px] font-bold uppercase tracking-wider text-white/40 shrink-0">{{ $page->total() }} item</span>
        </div>

        <section class="rounded-2xl border border-white/10 bg-white/[0.04] p-4 sm:p-5">
            @if(!empty($items))
                <div class="space-y-2">
                    @foreach($items as $item)
                        @include('profile._item', ['item' => $item])
                    @endforeach
                </div>

                @if($page->hasPages())
                    <div class="pt-5">{{ $page->links('partials.pagination') }}</div>
                @endif
            @else
                <div class="pr-empty">
                    <i class="fa-regular fa-folder-open"></i>
                    <p>Belum ada {{ strtolower($tabTitle) }}.</p>
                </div>
            @endif
        </section>
    </main>

    @include('partials.site-footer')
</body>
</html>
