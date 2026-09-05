{{-- Sub-halaman Laboratorium PAI: Tugas Siswa (list + redirect Google Classroom).
     Akses penuh = siswa terverifikasi / guru; belum → panel gate.
     Filter kelas client-side (_labor-scripts); urut deadline terdekat (controller). --}}
@php($pageTitle = 'Tugas Siswa - Laboratorium PAI')
@push('styles')@include('tsaqib._labor-styles')@endpush
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Daftar tugas Pendidikan Agama Islam per kelas — dikerjakan lewat Google Classroom.">
    <title>{{ $pageTitle }}</title>
    @include('partials.theme-head')
</head>
<body class="text-[var(--cream)] font-sans antialiased min-h-screen flex flex-col">

    @include('partials.navbar')

    <main class="flex-1 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14 w-full">

        {{-- Breadcrumb pojok kiri atas --}}
        <nav class="flex items-center gap-1.5 text-[10px]" aria-label="Breadcrumb">
            <a href="{{ route('laboratorium.pai') }}" class="font-semibold text-[var(--gold)]/80 hover:text-[var(--gold)] transition">Laboratorium PAI</a>
            <i class="fa-solid fa-chevron-right text-[7px] text-white/25" aria-hidden="true"></i>
            <span class="font-semibold text-white/65">Tugas Siswa</span>
        </nav>

        {{-- Header kiri — pola sama dgn halaman Modul --}}
        <div class="reveal" style="--reveal-i:0;">
            <h1 class="font-display font-extrabold text-[var(--cream)] text-2xl sm:text-4xl leading-[1.08] tracking-tight uppercase mt-4">
                Tugas Siswa
            </h1>
            <p class="text-white/60 text-xs sm:text-sm leading-relaxed mt-2.5 max-w-xl">
                Daftar tugas PAI per kelas. Pengumpulan dilakukan melalui Google Classroom — tombol tugas akan mengarahkanmu ke sana.
            </p>
        </div>

        <div class="mt-6 reveal" style="--reveal-i:1;">
            @include('tsaqib._labor-subnav', ['active' => 'tugas'])
        </div>

        @unless($verified)
            {{-- ===== Gate: belum terverifikasi ===== --}}
            <div class="tsaqib-card-flat p-12 text-center mt-10">
                <i class="fa-solid fa-lock text-3xl text-white/15 block mb-3"></i>
                <p class="text-white/60 text-sm font-semibold">Akun kamu belum terverifikasi sebagai siswa.</p>
                <p class="text-white/45 text-xs mt-1.5">Verifikasi NISN untuk melihat tugas kelasmu.</p>
                <a href="{{ auth()->check() ? route('verifikasi.siswa.form') : route('login') }}"
                   class="cta-primary inline-flex items-center gap-1.5 mt-5 text-white font-bold text-xs px-5 py-2.5 rounded-full">
                    <i class="fa-solid fa-id-card text-[10px]"></i>
                    {{ auth()->check() ? 'Verifikasi Sekarang' : 'Login Dulu' }}
                </a>
            </div>
        @else
            <div data-filter-root class="mt-7">
                {{-- ===== Grup filter berlabel — tab underline, pola sama dgn halaman Modul ===== --}}
                <div>
                    <p class="grp-label mb-2">Filter Kelas</p>
                    <div class="flex items-center gap-5 border-b border-white/10" role="group" aria-label="Filter kelas">
                        @foreach(['all' => 'Semua', 'X' => 'X', 'XI' => 'XI', 'XII' => 'XII'] as $val => $label)
                            <button type="button" data-filter-btn data-filter="kelas" data-value="{{ $val }}"
                                    class="u-tab {{ $loop->first ? 'is-active' : '' }}">{{ $label }}</button>
                        @endforeach
                    </div>
                </div>

                <p class="text-[11px] text-white/40 mt-6">
                    <span data-filter-count class="font-bold text-[var(--gold)]">{{ $tugas->count() }}</span> tugas aktif.
                </p>

                {{-- ===== List tugas — editorial, garis aksen emas kiri ===== --}}
                <div class="space-y-4 mt-5">
                    @forelse($tugas as $t)
                        @php($dl = $t->deadline)
                        <article data-filter-item
                                 data-kelas="{{ $t->target_kelas }}"
                                 data-search-text="{{ strtolower($t->judul_tugas . ' ' . ($t->user?->name ?? '')) }}"
                                 class="reveal labor-editorial tsaqib-card p-5"
                                 style="--reveal-i:{{ $loop->index % 6 }};">
                            <div class="flex flex-wrap items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <h3 class="font-display font-bold text-[15px] text-[var(--cream)] leading-snug">{{ $t->judul_tugas }}</h3>
                                    <p class="text-[11px] text-white/45 mt-1">{{ $t->user?->name ?? 'Guru PAI' }}</p>
                                </div>
                                <div class="flex items-center gap-2 shrink-0 flex-wrap">
                                    @if($t->target_kelas)
                                        <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-[#01795F]/15 text-[#3fd6b0] border border-[#01795F]/25">Kelas {{ $t->target_kelas }}</span>
                                    @endif
                                    @if($dl)
                                        <span class="inline-flex items-center gap-1 text-[10px] font-bold">
                                            <i class="fa-regular fa-calendar text-[9px] text-white/35"></i>
                                            <span class="text-white/55">{{ $dl->format('d M Y, H:i') }}</span>
                                            @php($days = (int) abs(now()->startOfDay()->diffInDays($dl->copy()->startOfDay())))
                                            @if($dl->isPast() && ! $dl->copy()->startOfDay()->isToday())
                                                <span class="px-2 py-0.5 rounded-full bg-red-500/15 text-red-300 border border-red-500/30">Terlambat</span>
                                            @elseif($days === 0)
                                                <span class="px-2 py-0.5 rounded-full bg-[rgba(201,166,107,0.16)] text-[var(--gold)] border border-[rgba(201,166,107,0.3)]">Hari ini</span>
                                            @elseif($days <= 2)
                                                <span class="px-2 py-0.5 rounded-full bg-[rgba(201,166,107,0.16)] text-[var(--gold)] border border-[rgba(201,166,107,0.3)]">H-{{ $days }}</span>
                                            @endif
                                        </span>
                                    @endif
                                </div>
                            </div>

                            @if($t->deskripsi)
                                <p class="text-xs text-white/55 leading-relaxed mt-3 line-clamp-3">{{ $t->deskripsi }}</p>
                            @endif

                            @if($t->link_google_classroom)
                                <div class="mt-4 pt-3 border-t border-white/5 flex flex-wrap items-center gap-3">
                                    <a href="{{ $t->link_google_classroom }}" target="_blank" rel="noopener"
                                       class="cta-primary inline-flex items-center gap-1.5 text-white font-bold text-[11px] px-4 py-2 rounded-full">
                                        <i class="fa-brands fa-google text-[10px]"></i>
                                        Kerjakan di Classroom <i class="fa-solid fa-arrow-up-right-from-square text-[9px]"></i>
                                    </a>
                                    <span class="text-[10px] text-white/35 inline-flex items-center gap-1">
                                        <i class="fa-solid fa-circle-info text-[9px]"></i>
                                        Anda akan diarahkan ke Google Classroom
                                    </span>
                                </div>
                            @endif
                        </article>
                    @empty
                        <div class="tsaqib-card-flat p-12 text-center">
                            <i class="fa-solid fa-clipboard-check text-3xl text-white/15 block mb-3"></i>
                            <p class="text-white/45 text-xs">Belum ada tugas aktif untuk kelas ini.</p>
                        </div>
                    @endforelse

                    {{-- Empty state hasil filter --}}
                    <div data-filter-empty class="hidden tsaqib-card-flat p-12 text-center">
                        <i class="fa-solid fa-clipboard-check text-3xl text-white/15 block mb-3"></i>
                        <p class="text-white/45 text-xs">Belum ada tugas aktif untuk kelas ini.</p>
                    </div>
                </div>
            </div>
        @endunless

    </main>

    @include('tsaqib._labor-scripts')

</body>
</html>
