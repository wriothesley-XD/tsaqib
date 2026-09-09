{{-- Sub-halaman Laboratorium PAI: Profil Laboratorium & Guru (publik, read-only) --}}
@php($pageTitle = 'Profil & Guru - Laboratorium PAI')
@push('styles')@include('tsaqib._labor-styles')@endpush
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Profil Laboratorium PAI dan para guru pengampu Pendidikan Agama Islam SMAN 1 Bukittinggi.">
    <title>{{ $pageTitle }}</title>
    @include('partials.theme-head')
</head>
<body class="text-[var(--cream)] font-sans antialiased min-h-screen flex flex-col">

    <!-- Unified TSAQIB Navbar -->
    @include('partials.navbar')

    <main class="flex-1 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14 w-full space-y-10">

        {{-- Breadcrumb pojok kiri atas --}}
        <nav class="flex items-center gap-1.5 text-[11px]" aria-label="Breadcrumb">
            <a href="{{ route('laboratorium.pai') }}" class="font-semibold text-[var(--gold)]/80 hover:text-[var(--gold)] transition">Laboratorium PAI</a>
            <i class="fa-solid fa-chevron-right text-[8px] text-white/30" aria-hidden="true"></i>
            <span class="font-semibold text-white/70">Profil &amp; Guru</span>
        </nav>

        {{-- Header Section --}}
        <div class="reveal" style="--reveal-i:0;">
            <span class="eyebrow-pill eyebrow-pill-gold mb-3 inline-flex items-center gap-1.5">
                <i class="fa-solid fa-chalkboard-user text-[10px]"></i>
                Pendidik &amp; Karakter Rabbani
            </span>
            <h1 class="font-display font-extrabold text-[var(--cream)] text-2xl sm:text-4xl leading-[1.1] tracking-tight uppercase mt-2">
                Profil Laboratorium &amp; <span class="text-[var(--gold)]">Guru Pengampu</span>
            </h1>
            <p class="text-white/60 text-xs sm:text-sm leading-relaxed mt-2.5 max-w-2xl">
                Sejarah, visi Laboratorium PAI, serta profil ustadz dan ustadzah pembina yang membimbing praktikum ibadah dan pembinaan karakter di SMAN 1 Bukittinggi.
            </p>
        </div>

        {{-- Sub-navigation --}}
        <div class="reveal" style="--reveal-i:1;">
            @include('tsaqib._labor-subnav', ['active' => 'profil'])
        </div>

        {{-- ===== Sejarah & Visi Rabbani ===== --}}
        <section class="reveal" style="--reveal-i:2;">
            <div class="tsaqib-card p-6 sm:p-8 relative overflow-hidden">
                <div class="absolute -right-6 -bottom-6 w-48 h-48 rounded-full bg-[var(--gold)]/5 blur-2xl pointer-events-none"></div>
                <p class="ed-eyebrow"><span class="text-[var(--gold)]/60">✦</span> Dari Ruang Laboratorium PAI</p>
                <h2 class="font-display font-extrabold text-lg sm:text-2xl text-[var(--cream)] tracking-tight uppercase mt-3">
                    Sejarah &amp; Visi Rabbani
                </h2>
                <p class="text-xs sm:text-sm text-white/70 leading-relaxed mt-3 max-w-2xl">
                    Laboratorium PAI adalah pusat riset, praktikum ibadah, dan pembinaan karakter
                    Pendidikan Agama Islam — bukan sekadar ruang fisik, melainkan wahana penguatan
                    akhlak mulia dan pembiasaan nilai keislaman dalam kehidupan sehari-hari peserta didik.
                </p>
                <div class="grid sm:grid-cols-2 gap-x-10 gap-y-6 mt-8 max-w-3xl">
                    <div class="vpoint">
                        <h4 class="font-display font-extrabold uppercase text-[var(--gold)]">Visi Rabbani</h4>
                        <p class="text-xs sm:text-sm mt-1.5 leading-relaxed">{{ $visiMisi['visi'] }}</p>
                    </div>
                    @foreach($visiMisi['misi'] as $misi)
                        <div class="vpoint">
                            <h4 class="font-display font-extrabold uppercase text-[var(--gold)]">Misi {{ $loop->iteration }}</h4>
                            <p class="text-xs sm:text-sm mt-1.5 leading-relaxed">{{ $misi }}</p>
                        </div>
                    @endforeach
                </div>
                <div class="mt-8 pt-6 border-t border-white/10 flex items-center justify-between">
                    <a href="{{ route('laboratorium.pai') }}" class="inline-flex items-center gap-2 text-xs font-bold text-[var(--gold)] hover:gap-3 transition-all">
                        <span>Lihat ikhtisar lengkap Laboratorium</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
            </div>
        </section>

        {{-- ===== Grid profil guru ===== --}}
        <section class="space-y-6">
            <div class="flex items-end justify-between gap-4 reveal" style="--reveal-i:0;">
                <div>
                    <span class="eyebrow-pill eyebrow-pill-green mb-2 inline-flex items-center gap-1.5">
                        <i class="fa-solid fa-users text-[10px]"></i>
                        Dewan Pengajar
                    </span>
                    <h2 class="font-display font-extrabold text-xl sm:text-2xl text-[var(--cream)]">
                        Guru <span class="text-[var(--gold)]">Pengampu</span>
                    </h2>
                    <p class="text-white/50 text-xs mt-1">{{ $gurus->count() }} guru terdaftar di Laboratorium PAI.</p>
                </div>
            </div>

            @if($gurus->isNotEmpty())
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach($gurus as $guru)
                        @php($kelasDiampu = $guru->kelas_diampu ?? [])
                        <article class="reveal tsaqib-card p-6 flex flex-col justify-between group hover:border-[var(--gold)]/40 transition-all duration-300" style="--reveal-i:{{ $loop->index % 6 }};">
                            <div>
                                <div class="flex items-start gap-4">
                                    @if($guru->foto_path)
                                        <img src="{{ asset('storage/' . $guru->foto_path) }}" alt="{{ $guru->user?->name }}"
                                             class="w-16 h-16 rounded-2xl object-cover border-2 border-[var(--gold)]/40 shadow-md shrink-0 group-hover:scale-105 transition-transform duration-300">
                                    @else
                                        <span class="inline-flex items-center justify-center w-16 h-16 rounded-2xl shrink-0 border-2 border-[var(--gold)]/40 bg-[rgba(201,166,107,0.12)] text-[var(--gold)] shadow-md group-hover:scale-105 transition-transform duration-300">
                                            <i class="fa-solid fa-user text-xl"></i>
                                        </span>
                                    @endif
                                    <div class="min-w-0 flex-1">
                                        <h3 class="font-display font-bold text-[15px] text-[var(--cream)] leading-snug truncate group-hover:text-[var(--gold)] transition-colors">
                                            {{ $guru->user?->name ?? 'Guru PAI' }}
                                        </h3>
                                        @if($guru->nip)
                                            <p class="text-[10px] text-white/45 mt-0.5 font-mono">NIP. {{ $guru->nip }}</p>
                                        @endif
                                        @if($guru->mapel_pengampu)
                                            <span class="inline-flex items-center mt-2 px-2.5 py-0.5 rounded-full border text-[9px] font-bold uppercase tracking-wider bg-[rgba(201,166,107,0.14)] text-[var(--gold)] border-[rgba(201,166,107,0.28)]">
                                                {{ $guru->mapel_pengampu }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                @if(!empty($kelasDiampu))
                                    <div class="flex flex-wrap gap-1.5 mt-4 pt-3 border-t border-white/5">
                                        @foreach($kelasDiampu as $kelas)
                                            <span class="px-2.5 py-0.5 rounded-full text-[9px] font-bold bg-[#01795F]/15 text-[#3fd6b0] border border-[#01795F]/25">
                                                Kelas {{ $kelas }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <div class="mt-5 pt-4 border-t border-white/10 flex items-center justify-between">
                                @if($guru->wa_number)
                                    <a href="https://wa.me/{{ preg_replace('/\D/', '', $guru->wa_number) }}" target="_blank" rel="noopener"
                                       class="inline-flex items-center gap-2 text-xs font-bold text-[#3fd6b0] hover:text-[var(--gold)] transition cursor-pointer">
                                        <i class="fa-brands fa-whatsapp text-sm"></i>
                                        <span>Hubungi WhatsApp</span>
                                    </a>
                                @else
                                    <span class="text-[10px] text-white/35 italic">Kontak privat</span>
                                @endif
                                <span class="w-2 h-2 rounded-full bg-[#01795F]/40"></span>
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="tsaqib-card-flat p-12 text-center rounded-2xl">
                    <div class="w-14 h-14 rounded-full bg-white/5 flex items-center justify-center mx-auto mb-3 text-white/30 text-xl">
                        <i class="fa-solid fa-user-tie"></i>
                    </div>
                    <p class="text-white/60 text-sm font-semibold">Belum Ada Profil Guru</p>
                    <p class="text-white/40 text-xs mt-1">Data guru pengampu Laboratorium PAI akan segera diperbarui.</p>
                </div>
            @endif
        </section>

    </main>

    <!-- Footer -->
    @include('partials.site-footer')

    @include('tsaqib._labor-scripts')
</body>
</html>
