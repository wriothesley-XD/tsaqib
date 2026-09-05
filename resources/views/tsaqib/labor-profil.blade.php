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

    @include('partials.navbar')

    <main class="flex-1 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14 w-full space-y-10">

        {{-- Breadcrumb pojok kiri atas --}}
        <nav class="flex items-center gap-1.5 text-[10px]" aria-label="Breadcrumb">
            <a href="{{ route('laboratorium.pai') }}" class="font-semibold text-[var(--gold)]/80 hover:text-[var(--gold)] transition">Laboratorium PAI</a>
            <i class="fa-solid fa-chevron-right text-[7px] text-white/25" aria-hidden="true"></i>
            <span class="font-semibold text-white/65">Profil &amp; Guru</span>
        </nav>

        {{-- Header kiri — pola sama dgn halaman Modul & Tugas --}}
        <div class="reveal" style="--reveal-i:0;">
            <h1 class="font-display font-extrabold text-[var(--cream)] text-2xl sm:text-4xl leading-[1.08] tracking-tight uppercase mt-4">
                Profil Laboratorium &amp; <span class="text-[var(--gold)]">Guru Pengampu</span>
            </h1>
            <p class="text-white/60 text-xs sm:text-sm leading-relaxed mt-2.5 max-w-xl">
                Sejarah, visi Laboratorium PAI, serta para guru pembina yang mengampu praktikum dan pembinaan karakter.
            </p>
        </div>

        <div class="reveal" style="--reveal-i:1;">
            @include('tsaqib._labor-subnav', ['active' => 'profil'])
        </div>

        {{-- ===== Sejarah & Visi Rabbani (konten existing — vpoint pola Bina Karakter) ===== --}}
        <section class="reveal" style="--reveal-i:0;">
            <div class="tsaqib-card p-6 sm:p-8">
                <p class="ed-eyebrow"><span class="text-[var(--gold)]/60">✦</span> Dari Ruang Laboratorium PAI</p>
                <h2 class="font-display font-extrabold text-lg sm:text-xl text-[var(--cream)] tracking-tight uppercase mt-3">
                    Sejarah &amp; Visi Rabbani
                </h2>
                <p class="text-sm text-white/65 leading-relaxed mt-3 max-w-2xl">
                    Laboratorium PAI adalah pusat riset, praktikum ibadah, dan pembinaan karakter
                    Pendidikan Agama Islam — bukan sekadar ruang fisik, melainkan wahana penguatan
                    akhlak mulia dan pembiasaan nilai keislaman dalam kehidupan sehari-hari peserta didik.
                </p>
                <div class="grid sm:grid-cols-2 gap-x-10 gap-y-5 mt-6 max-w-3xl">
                    <div class="vpoint">
                        <h4 class="font-display font-extrabold uppercase">Visi Rabbani</h4>
                        <p class="text-sm mt-1">{{ $visiMisi['visi'] }}</p>
                    </div>
                    @foreach($visiMisi['misi'] as $misi)
                        <div class="vpoint">
                            <h4 class="font-display font-extrabold uppercase">Misi</h4>
                            <p class="text-sm mt-1">{{ $misi }}</p>
                        </div>
                    @endforeach
                </div>
                <a href="{{ route('laboratorium.pai') }}" class="inline-flex items-center gap-1.5 mt-6 text-xs font-bold text-[var(--gold)] hover:gap-2.5 transition-all">
                    Ikhtisar lengkap Laboratorium <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>
        </section>

        {{-- ===== Grid profil guru ===== --}}
        <section>
            <div class="flex items-end justify-between gap-4 mb-5 reveal" style="--reveal-i:0;">
                <div>
                    <h2 class="font-display font-extrabold text-lg text-[var(--cream)]">
                        Guru <span class="text-[var(--gold)]">Pengampu</span>
                    </h2>
                    <p class="text-white/50 text-xs mt-1">{{ $gurus->count() }} guru terdaftar di Laboratorium PAI.</p>
                </div>
            </div>

            @if($gurus->isNotEmpty())
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">
                    @foreach($gurus as $guru)
                        @php($kelasDiampu = $guru->kelas_diampu ?? [])
                        <article class="reveal tsaqib-card p-5 flex flex-col" style="--reveal-i:{{ $loop->index % 6 }};">
                            <div class="flex items-center gap-3.5">
                                @if($guru->foto_path)
                                    <img src="{{ asset('storage/' . $guru->foto_path) }}" alt="{{ $guru->user?->name }}"
                                         class="w-14 h-14 rounded-full object-cover border-2 border-[var(--gold)]/40 shrink-0">
                                @else
                                    <span class="inline-flex items-center justify-center w-14 h-14 rounded-full shrink-0 border-2 border-[var(--gold)]/40 bg-[rgba(201,166,107,0.12)] text-[var(--gold)]">
                                        <i class="fa-solid fa-user text-lg"></i>
                                    </span>
                                @endif
                                <div class="min-w-0">
                                    <h3 class="font-display font-bold text-[15px] text-[var(--cream)] leading-snug truncate">{{ $guru->user?->name ?? 'Guru' }}</h3>
                                    @if($guru->nip)
                                        <p class="text-[10px] text-white/40 mt-0.5">NIP. {{ $guru->nip }}</p>
                                    @endif
                                    @if($guru->mapel_pengampu)
                                        <span class="inline-flex items-center mt-1.5 px-2 py-0.5 rounded-full border text-[9px] font-bold uppercase tracking-wider bg-[rgba(201,166,107,0.16)] text-[var(--gold)] border-[rgba(201,166,107,0.3)]">
                                            {{ $guru->mapel_pengampu }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            @if(!empty($kelasDiampu))
                                <div class="flex flex-wrap gap-1.5 mt-4">
                                    @foreach($kelasDiampu as $kelas)
                                        <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-[#01795F]/15 text-[#3fd6b0] border border-[#01795F]/25">{{ $kelas }}</span>
                                    @endforeach
                                </div>
                            @endif

                            <div class="mt-auto pt-4">
                                @if($guru->wa_number)
                                    <a href="https://wa.me/{{ preg_replace('/\D/', '', $guru->wa_number) }}" target="_blank" rel="noopener"
                                       class="inline-flex items-center gap-1.5 text-[11px] font-bold text-[#3fd6b0] hover:text-[var(--gold)] transition cursor-pointer">
                                        <i class="fa-brands fa-whatsapp text-sm"></i> Kontak WhatsApp
                                    </a>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="tsaqib-card-flat p-12 text-center">
                    <i class="fa-solid fa-user-tie text-3xl text-white/15 block mb-3"></i>
                    <p class="text-white/45 text-xs">Belum ada profil guru yang ditambahkan.</p>
                </div>
            @endif
        </section>

    </main>

    @include('tsaqib._labor-scripts')
</body>
</html>
