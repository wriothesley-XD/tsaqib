{{-- Sub-halaman Laboratorium PAI: Tugas Siswa (list + redirect Google Classroom). --}}
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

    <!-- Unified TSAQIB Navbar -->
    @include('partials.navbar')

    <main class="flex-1 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14 w-full space-y-8">

        {{-- Breadcrumb pojok kiri atas --}}
        <nav class="flex items-center gap-1.5 text-[11px]" aria-label="Breadcrumb">
            <a href="{{ route('laboratorium.pai') }}" class="font-semibold text-[var(--gold)]/80 hover:text-[var(--gold)] transition">Laboratorium PAI</a>
            <i class="fa-solid fa-chevron-right text-[8px] text-white/30" aria-hidden="true"></i>
            <span class="font-semibold text-white/70">Tugas Siswa</span>
        </nav>

        {{-- Header Section --}}
        <div class="reveal" style="--reveal-i:0;">
            <span class="eyebrow-pill eyebrow-pill-green mb-3 inline-flex items-center gap-1.5">
                <i class="fa-solid fa-list-check text-[10px]"></i>
                Penugasan &amp; Evaluasi
            </span>
            <h1 class="font-display font-extrabold text-[var(--cream)] text-2xl sm:text-4xl leading-[1.1] tracking-tight uppercase mt-1">
                Tugas <span class="text-[var(--gold)]">Siswa</span>
            </h1>
            <p class="text-white/60 text-xs sm:text-sm leading-relaxed mt-2.5 max-w-xl">
                Daftar tugas terstruktur PAI per tingkatan kelas. Pengumpulan berkas dilakukan secara daring melalui Google Classroom resmi sekolah.
            </p>
        </div>

        {{-- Sub-navigation --}}
        <div class="reveal" style="--reveal-i:1;">
            @include('tsaqib._labor-subnav', ['active' => 'tugas'])
        </div>

        @unless($verified)
            {{-- ===== Gate: belum terverifikasi NISN ===== --}}
            <div class="tsaqib-card p-8 sm:p-12 text-center max-w-xl mx-auto my-12 rounded-3xl relative overflow-hidden">
                <div class="w-16 h-16 rounded-2xl bg-[var(--gold)]/15 border border-[var(--gold)]/30 text-[var(--gold)] flex items-center justify-center mx-auto mb-4 text-2xl shadow-lg">
                    <i class="fa-solid fa-lock"></i>
                </div>
                <span class="eyebrow-pill eyebrow-pill-gold mb-3 inline-flex items-center gap-1.5">
                    <i class="fa-solid fa-id-card text-[10px]"></i>
                    Akses Khusus Siswa Terdaftar NISN
                </span>
                <h2 class="text-xl sm:text-2xl font-display font-bold text-[var(--cream)] mb-2 mt-2">
                    Akses Tugas &amp; Google Classroom Terkunci
                </h2>
                <p class="text-white/60 text-xs sm:text-sm leading-relaxed max-w-md mx-auto">
                    Info pengumpulan tugas dan tautan Google Classroom hanya muncul jika login menggunakan akun siswa yang terdaftar dengan <strong>NISN resmi SMAN 1 Bukittinggi</strong> atau akun pengajar.
                </p>
                <div class="mt-6 flex flex-col sm:flex-row items-center justify-center gap-3">
                    <a href="{{ auth()->check() ? route('verifikasi.siswa.form') : route('login') }}"
                       class="btn-gold text-xs px-6 py-3 w-full sm:w-auto">
                        <i class="fa-solid fa-id-badge"></i>
                        <span>{{ auth()->check() ? 'Verifikasi NISN Sekarang' : 'Masuk dengan NISN / Email' }}</span>
                    </a>
                    <a href="{{ route('laboratorium.pai') }}"
                       class="btn-outline text-xs px-6 py-3 w-full sm:w-auto">
                        <span>Kembali ke Ikhtisar</span>
                    </a>
                </div>
            </div>
        @else
            <div data-filter-root class="space-y-6">
                {{-- ===== Grup filter kelas ===== --}}
                <div class="tsaqib-card p-5 sm:p-6 reveal" style="--reveal-i:2;">
                    <p class="grp-label mb-2 text-white/40">Tingkat Kelas</p>
                    <div class="flex items-center gap-6 border-b border-white/10 pb-1" role="group" aria-label="Filter kelas">
                        @foreach(['all' => 'Semua Kelas', 'X' => 'Kelas X', 'XI' => 'Kelas XI', 'XII' => 'Kelas XII'] as $val => $label)
                            <button type="button" data-filter-btn data-filter="kelas" data-value="{{ $val }}"
                                    class="u-tab {{ $loop->first ? 'is-active' : '' }} text-xs font-semibold">{{ $label }}</button>
                        @endforeach
                    </div>
                </div>

                <div class="flex items-center justify-between text-xs text-white/45">
                    <p>
                        <span data-filter-count class="font-bold text-[var(--gold)]">{{ $tugas->count() }}</span> tugas aktif terdata.
                    </p>
                    <span class="text-[11px]"><i class="fa-brands fa-google text-[var(--gold)] mr-1"></i>Tersinkronisasi Google Classroom</span>
                </div>

                {{-- ===== List tugas ===== --}}
                <div class="space-y-4">
                    @forelse($tugas as $t)
                        @php($dl = $t->deadline)
                        <article data-filter-item
                                 data-kelas="{{ $t->target_kelas }}"
                                 data-search-text="{{ strtolower($t->judul_tugas . ' ' . ($t->user?->name ?? '')) }}"
                                 class="reveal tsaqib-card p-5 sm:p-6 hover:border-[var(--gold)]/40 transition-all duration-300"
                                 style="--reveal-i:{{ $loop->index % 6 }};">
                            <div class="flex flex-wrap items-start justify-between gap-4">
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <h3 class="font-display font-bold text-base sm:text-lg text-[var(--cream)] leading-snug">
                                            {{ $t->judul_tugas }}
                                        </h3>
                                        @if($t->target_kelas)
                                            <span class="px-2.5 py-0.5 rounded-full text-[9px] font-bold bg-[#01795F]/15 text-[#3fd6b0] border border-[#01795F]/25">
                                                Kelas {{ $t->target_kelas }}
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-white/45 mt-1">
                                        Pengampu: {{ $t->user?->name ?? 'Guru PAI' }}
                                    </p>
                                </div>

                                <div class="flex items-center gap-2 shrink-0 flex-wrap">
                                    @if($dl)
                                        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-black/30 border border-white/10 text-xs font-semibold">
                                            <i class="fa-regular fa-calendar text-[var(--gold)]"></i>
                                            <span class="text-white/70">{{ $dl->format('d M Y, H:i') }} WIB</span>
                                            @php($days = (int) abs(now()->startOfDay()->diffInDays($dl->copy()->startOfDay())))
                                            @if($dl->isPast() && ! $dl->copy()->startOfDay()->isToday())
                                                <span class="px-2 py-0.5 rounded-full bg-red-500/20 text-red-300 border border-red-500/30 text-[10px] font-bold">Terlambat</span>
                                            @elseif($days === 0)
                                                <span class="px-2 py-0.5 rounded-full bg-[rgba(201,166,107,0.2)] text-[var(--gold)] border border-[rgba(201,166,107,0.35)] text-[10px] font-bold">Hari ini</span>
                                            @elseif($days <= 2)
                                                <span class="px-2 py-0.5 rounded-full bg-[rgba(201,166,107,0.2)] text-[var(--gold)] border border-[rgba(201,166,107,0.35)] text-[10px] font-bold">H-{{ $days }}</span>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>

                            @if($t->deskripsi)
                                <p class="text-xs sm:text-sm text-white/60 leading-relaxed mt-3.5 bg-black/20 p-3.5 rounded-xl border border-white/5">
                                    {{ $t->deskripsi }}
                                </p>
                            @endif

                            @if($t->link_google_classroom)
                                <div class="mt-4 pt-4 border-t border-white/10 flex flex-wrap items-center justify-between gap-3">
                                    @php($hasNisnAccess = !empty(auth()->user()?->nisn) || in_array(auth()->user()?->role, ['admin', 'guru'], true))
                                    @if($hasNisnAccess)
                                        <a href="{{ $t->link_google_classroom }}" target="_blank" rel="noopener"
                                           class="btn-primary text-xs px-4 py-2">
                                            <i class="fa-brands fa-google text-[11px]"></i>
                                            <span>Buka di Google Classroom</span>
                                            <i class="fa-solid fa-arrow-up-right-from-square text-[10px] ml-1"></i>
                                        </a>
                                        <span class="text-[11px] text-white/40 inline-flex items-center gap-1.5">
                                            <i class="fa-solid fa-shield-halved text-[#3fd6b0]"></i>
                                            Akses terbuka (NISN Terverifikasi)
                                        </span>
                                    @else
                                        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-amber-500/10 border border-amber-500/25 text-amber-300 text-xs">
                                            <i class="fa-solid fa-lock text-[var(--gold)]"></i>
                                            <span>Classroom terkunci — wajib mendaftar/login dengan NISN terdaftar</span>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </article>
                    @empty
                        <div class="tsaqib-card-flat p-12 text-center rounded-2xl">
                            <div class="w-14 h-14 rounded-full bg-white/5 flex items-center justify-center mx-auto mb-3 text-white/30 text-xl">
                                <i class="fa-solid fa-clipboard-check"></i>
                            </div>
                            <p class="text-white/60 text-sm font-semibold">Tidak Ada Tugas Aktif</p>
                            <p class="text-white/40 text-xs mt-1">Saat ini belum ada tugas baru yang perlu dikerjakan.</p>
                        </div>
                    @endforelse

                    {{-- Empty state hasil filter --}}
                    <div data-filter-empty class="hidden tsaqib-card-flat p-12 text-center rounded-2xl">
                        <div class="w-14 h-14 rounded-full bg-white/5 flex items-center justify-center mx-auto mb-3 text-white/30 text-xl">
                            <i class="fa-solid fa-clipboard-check"></i>
                        </div>
                        <p class="text-white/60 text-sm font-semibold">Tidak Ada Tugas</p>
                        <p class="text-white/40 text-xs mt-1">Belum ada tugas yang cocok dengan filter kelas yang dipilih.</p>
                    </div>
                </div>
            </div>
        @endunless

    </main>

    <!-- Footer -->
    @include('partials.site-footer')

    @include('tsaqib._labor-scripts')

</body>
</html>
