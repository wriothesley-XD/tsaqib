{{-- Sub-halaman Laboratorium PAI: Profil Laboratorium & Guru (publik, read-only) --}}
@php($pageTitle = 'Profil & Guru - Laboratorium PAI')
@push('styles')
    @include('tsaqib._labor-styles')
    <style>
    /* Card Profil Guru Pengampu (Adapted from Uiverse.io by Smit-Prajapati) */
    .guru-card {
      width: 280px;
      height: 280px;
      background: #01795F;
      border-radius: 32px;
      padding: 3px;
      position: relative;
      box-shadow: #01795f55 0px 70px 30px -50px;
      transition: all 0.5s ease-in-out;
    }

    .guru-card .mail {
      position: absolute;
      right: 1.5rem;
      top: 1.2rem;
      background: transparent;
      border: none;
      z-index: 10;
      display: flex;
      align-items: center;
      justify-content: center;
      text-decoration: none;
      cursor: pointer;
    }

    .guru-card .mail svg {
      stroke: #3fd6b0;
      stroke-width: 3px;
      transition: stroke 0.3s ease;
    }

    .guru-card .mail:hover svg {
      stroke: #a8f0dc;
    }

    .guru-card .profile-pic {
      position: absolute;
      width: calc(100% - 6px);
      height: calc(100% - 6px);
      top: 3px;
      left: 3px;
      border-radius: 29px;
      z-index: 1;
      border: 0px solid #3fd6b0;
      overflow: hidden;
      transition: all 0.5s ease-in-out 0.2s, z-index 0.5s ease-in-out 0.2s;
      background: #e8f5f1;
    }

    .guru-card .profile-pic img {
      -o-object-fit: cover;
      object-fit: cover;
      width: 100%;
      height: 100%;
      object-position: center center;
      transition: all 0.5s ease-in-out 0s;
    }

    .guru-card .profile-pic svg {
      width: 100%;
      height: 100%;
      -o-object-fit: cover;
      object-fit: cover;
      object-position: center center;
      transition: all 0.5s ease-in-out 0s;
    }

    .guru-card .bottom {
      position: absolute;
      bottom: 3px;
      left: 3px;
      right: 3px;
      background: #01795F;
      top: 80%;
      border-radius: 29px;
      z-index: 2;
      box-shadow: rgba(1, 121, 95, 0.25) 0px 5px 5px 0px inset;
      overflow: hidden;
      transition: all 0.5s cubic-bezier(0.645, 0.045, 0.355, 1) 0s;
    }

    .guru-card .bottom .content {
      position: absolute;
      bottom: 0;
      left: 1.25rem;
      right: 1.25rem;
      height: 160px;
    }

    .guru-card .bottom .content .name {
      display: block;
      font-size: 1.05rem;
      color: white;
      font-weight: bold;
      line-height: 1.25;
    }

    .guru-card .bottom .content .about-me {
      display: -webkit-box;
      -webkit-line-clamp: 4;
      -webkit-box-orient: vertical;
      overflow: hidden;
      font-size: 0.78rem;
      color: rgba(255, 255, 255, 0.85);
      margin-top: 0.5rem;
      line-height: 1.35;
      text-shadow: 0 1px 2px rgba(0, 0, 0, 0.15);
    }

    .guru-card .bottom .bottom-bottom {
      position: absolute;
      bottom: 0.85rem;
      left: 1.25rem;
      right: 1.25rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .guru-card .bottom .bottom-bottom .social-links-container {
      display: flex;
      gap: 0.75rem;
      align-items: center;
    }

    .guru-card .bottom .bottom-bottom .social-links-container a,
    .guru-card .bottom .bottom-bottom .social-links-container span {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      text-decoration: none;
    }

    .guru-card .bottom .bottom-bottom .social-links-container svg {
      height: 18px;
      width: 18px;
      fill: white;
      filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.15));
      transition: all 0.3s ease;
    }

    .guru-card .bottom .bottom-bottom .social-links-container svg:hover {
      fill: #a8f0dc;
      transform: scale(1.25);
    }

    .guru-card .bottom .bottom-bottom .button {
      background: white;
      color: #01795F;
      border: none;
      border-radius: 20px;
      font-size: 0.62rem;
      font-weight: 700;
      padding: 0.38rem 0.75rem;
      box-shadow: rgba(1, 121, 95, 0.2) 0px 5px 5px 0px;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      transition: all 0.3s ease;
      cursor: pointer;
    }

    .guru-card .bottom .bottom-bottom .button:hover {
      background: #3fd6b0;
      color: #01412f;
    }

    .guru-card:hover {
      border-top-left-radius: 55px;
    }

    .guru-card:hover .bottom {
      top: 20%;
      border-radius: 80px 29px 29px 29px;
      transition: all 0.5s cubic-bezier(0.645, 0.045, 0.355, 1) 0.2s;
    }

    .guru-card:hover .profile-pic {
      width: 100px;
      height: 100px;
      aspect-ratio: 1;
      top: 10px;
      left: 10px;
      border-radius: 50%;
      z-index: 3;
      border: 4px solid #3fd6b0;
      box-shadow: rgba(1, 121, 95, 0.35) 0px 5px 12px 0px;
      transition: all 0.5s ease-in-out, z-index 0.5s ease-in-out 0.1s;
    }

    .guru-card:hover .profile-pic img {
      transform: scale(1);
      object-fit: cover;
      object-position: center center;
      transition: all 0.5s ease-in-out 0.3s;
    }

    .guru-card:hover .profile-pic svg {
      transform: scale(1);
      transition: all 0.5s ease-in-out 0.3s;
    }
    </style>
@endpush
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
                <div class="flex flex-wrap items-center justify-center gap-8 py-4">
                    @foreach($gurus as $guru)
                        <div class="guru-card reveal" style="--reveal-i:{{ $loop->index % 6 }};">
                            <a href="{{ $guru->email ? 'mailto:' . $guru->email : '#' }}" class="mail" title="{{ $guru->email ? 'Email: ' . $guru->email : 'Email belum diisi' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect width="20" height="16" x="2" y="4" rx="2"></rect>
                                    <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
                                </svg>
                            </a>
                            <div class="profile-pic">
                                @if($guru->foto_path)
                                    <img src="{{ asset('storage/' . $guru->foto_path) }}" alt="{{ $guru->nama }}">
                                @else
                                    @include('tsaqib.partials.guru-avatar-svg')
                                @endif
                            </div>
                            <div class="bottom">
                                <div class="content">
                                    <span class="name truncate" title="{{ $guru->nama }}">{{ $guru->nama }}</span>
                                    @if($guru->mapel_pengampu)
                                        <span class="block text-[10px] font-bold text-white/90 uppercase tracking-wider mt-0.5">{{ $guru->mapel_pengampu }}</span>
                                    @endif
                                    @if(!empty($guru->kelas_diampu))
                                        <span class="block text-[9px] text-white/75 font-semibold mt-0.5">
                                            Kelas: {{ implode(', ', $guru->kelas_diampu) }}
                                        </span>
                                    @endif
                                    <span class="about-me" title="{{ $guru->deskripsi }}">
                                        {{ $guru->deskripsi ?: 'Guru Pengampu Pendidikan Agama Islam SMAN 1 Bukittinggi.' }}
                                    </span>
                                </div>
                                <div class="bottom-bottom">
                                    <div class="social-links-container">
                                        @if($guru->facebook_url)
                                            <a href="{{ $guru->facebook_url }}" target="_blank" rel="noopener noreferrer" title="Facebook: {{ $guru->nama }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                                                    <path d="M80 299.3V512H196V299.3h86.5l18-97.8H196V136.9c0-26.6 13.1-52.4 54.7-52.4H296V.8S255.4 0 216.7 0C136.2 0 80 49.3 80 137.9v63.6H0v97.8h80z"/>
                                                </svg>
                                            </a>
                                        @else
                                            <span title="Facebook belum tersedia" class="opacity-40 cursor-not-allowed">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                                                    <path d="M80 299.3V512H196V299.3h86.5l18-97.8H196V136.9c0-26.6 13.1-52.4 54.7-52.4H296V.8S255.4 0 216.7 0C136.2 0 80 49.3 80 137.9v63.6H0v97.8h80z"/>
                                                </svg>
                                            </span>
                                        @endif

                                        @if($guru->instagram_url)
                                            @php($igLink = str_starts_with($guru->instagram_url, 'http') ? $guru->instagram_url : 'https://instagram.com/' . ltrim($guru->instagram_url, '@'))
                                            <a href="{{ $igLink }}" target="_blank" rel="noopener noreferrer" title="Instagram: {{ $guru->nama }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                                                    <path d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1z"/>
                                                </svg>
                                            </a>
                                        @else
                                            <span title="Instagram belum tersedia" class="opacity-40 cursor-not-allowed">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                                                    <path d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1z"/>
                                                </svg>
                                            </span>
                                        @endif
                                    </div>

                                    @if($guru->wa_number)
                                        <a href="https://wa.me/{{ preg_replace('/\D/', '', $guru->wa_number) }}" target="_blank" rel="noopener noreferrer" class="button">Hubungi</a>
                                    @elseif($guru->email)
                                        <a href="mailto:{{ $guru->email }}" class="button">Hubungi</a>
                                    @else
                                        <span class="button">Hubungi</span>
                                    @endif
                                </div>
                            </div>
                        </div>
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
