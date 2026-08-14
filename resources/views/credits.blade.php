{{-- resources/views/credits.blade.php --}}
{{-- Halaman Credits (tim pengembang). UNLISTED — hanya dicapai via logo Liivo di
     footer. $tim datang dari PageController::credits() sebagai
     [nama, peran, tag, accent, tagline, socials, avatar_url].
     Dibangun pakai Tailwind utilities + token tema bersama (font-display, --gold,
     --cream, .eyebrow-pill, .tsaqib-card) — sama persis polanya dgn halaman publik
     lain (/profile, /komunitas, dst) supaya render-nya konsisten & stabil. --}}
@php($pageTitle = 'Credits - TSAQIB SMAN 1 Bukittinggi')
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    @include('partials.theme-head')

    @push('styles')
    <style>
        /* Atmosfer halaman: glow emerald samar di puncak, di atas --ink. */
        body{
            background:
                radial-gradient(circle at 50% -10%, rgba(1,121,95,.16), transparent 45%),
                var(--ink);
        }

        /* HANYA bagian yg butuh --accent per-anggota yg dipakai di sini; sisanya
           memakai Tailwind utilities + token tema bersama.
           TANPA overflow:hidden — tinggi kartu tumbuh mengikuti konten (flex-col,
           auto), jadi tagline 2-baris tidak pernah terpotong di tepi bawah. */
        .dev-card{ --accent: var(--gold); position:relative; }
        /* Glow accent samar di belakang avatar; terang saat hover.
           top:0 (BUKAN -34px) menempatkan halo 130px tepat di belakang avatar 80px
           DAN sepenuhnya di dalam kartu → tidak butuh overflow:hidden utk
           menahannya, sehingga konten tidak ikut ter-clip. */
        .dev-card::before{
            content:""; position:absolute; top:0; left:50%; transform:translateX(-50%);
            width:130px; height:130px; border-radius:9999px; pointer-events:none;
            background:var(--accent); opacity:.14; filter:blur(22px); transition:opacity .25s ease;
        }
        .dev-card:hover::before{ opacity:.26; }

        /* Cincin avatar ber-tint accent. */
        .dev-avatar-ring{
            background:color-mix(in srgb, var(--accent) 20%, transparent);
            transition:background .25s ease;
        }
        .dev-card:hover .dev-avatar-ring{ background:color-mix(in srgb, var(--accent) 32%, transparent); }

        /* Tagline: satu kata <em> disorot accent. */
        .dev-tagline em{ color:var(--accent); font-style:normal; font-weight:600; }
    </style>
    @endpush
</head>
<body class="text-[var(--cream)] font-sans antialiased min-h-screen flex flex-col">

    <!-- Unified TSAQIB Navbar -->
    @include('partials.navbar')

    <!-- Credits Content -->
    <main class="flex-1">
        {{-- ===== HEADER ===== --}}
        <header class="text-center px-4 sm:px-6 pt-14 sm:pt-20 pb-10 sm:pb-14">
            <div class="max-w-[600px] mx-auto">
                <span class="eyebrow-pill eyebrow-pill-green">
                    <i class="fa-solid fa-clapperboard text-[10px]"></i>
                    Di Balik Layar
                </span>
                <h1 class="font-display font-extrabold text-3xl sm:text-4xl tracking-tight mt-4 text-[var(--cream)]">
                    Tim <span class="text-[var(--gold)]">Pengembang</span>
                </h1>
                <p class="text-white/60 text-sm sm:text-[15px] leading-relaxed mt-3">
                    Platform TSAQIB dirancang dan dibangun oleh tim LIIVO, bekerja sama erat dengan
                    Forum Studi Islam SMAN 1 Bukittinggi untuk menghadirkan pengalaman digital yang
                    menghubungkan Laboratorium PAI, Perpustakaan, Komunitas, dan Buletin dalam satu
                    ekosistem — dirawat dan terus dikembangkan seiring kebutuhan FSI.
                </p>
            </div>
        </header>

        {{-- ===== GRID ANGGOTA ===== --}}
        <div class="max-w-5xl mx-auto w-full px-4 sm:px-6 pb-14 sm:pb-20">
            {{-- Section title --}}
            <div class="text-center mb-8 sm:mb-10">
                <div class="text-[11px] font-semibold tracking-[0.2em] uppercase text-white/50">Tim Inti</div>
                <h2 class="font-display font-bold text-xl sm:text-2xl mt-1.5 text-[var(--cream)]">
                    Dibuat oleh Anggota <span class="text-[var(--gold)]">LIIVO</span>
                </h2>
            </div>

            {{-- $tim: [nama, peran, tag, accent, tagline, socials, avatar_url, has_img].
                 Layout: flex-wrap + justify-center agar baris terakhir yang tak penuh
                 (7 anggota → 4 + 3) TENGAH, bukan rata kiri. Lebar kartu per breakpoint:
                 mobile 1/brs · sm 2/brs · lg 4/brs (calc() mengakomodasi gap-5 = 20px). --}}
            <div class="flex flex-wrap justify-center gap-5">
                @foreach($tim as $m)
                    <article class="dev-card tsaqib-card w-full sm:w-[calc(50%-10px)] lg:w-[calc(25%-15px)] flex flex-col items-center text-center !p-6" style="--accent:{{ $m['accent'] }}">
                        {{-- Avatar: 80px bulat, cincin tint accent. has_img dicek via file_exists
                             di controller — true hanya bila foto asli sudah ada di disk.
                             SWAP PLACEHOLDER → FOTO ASLI: drop file di public/assets/team/{slug}.jpg
                             (lihat key 'avatar' di PageController::credits()). --}}
                        <div class="dev-avatar-ring relative z-[1] rounded-full p-[3px]">
                            @if($m['has_img'])
                                <img src="{{ $m['avatar_url'] }}" alt="{{ $m['nama'] }}"
                                     class="h-20 w-20 rounded-full object-cover bg-[#161a14] block"
                                     style="box-shadow:inset 0 0 0 1px rgba(247,245,239,.08);"
                                     loading="lazy">
                            @else
                                {{-- PLACEHOLDER: ikon orang di lingkaran tint accent. Tanda jelas
                                     bahwa foto asli belum di-upload (bukan wajah, bukan gradient kosong). --}}
                                <span class="h-20 w-20 rounded-full flex items-center justify-center block"
                                      style="background:color-mix(in srgb, var(--accent) 22%, transparent);
                                             box-shadow:inset 0 0 0 1px rgba(247,245,239,.08);"
                                      aria-label="Foto {{ $m['nama'] }} belum diunggah">
                                    <i class="fa-solid fa-user text-2xl"
                                       style="color:color-mix(in srgb, var(--accent) 80%, white);"></i>
                                </span>
                            @endif
                        </div>

                        {{-- Nama + peran panjang. (Tag pill dihapus — nama langsung dari avatar, mt-4.) --}}
                        <h3 class="relative z-[1] font-display font-bold text-[17px] mt-4 text-[var(--cream)]">{{ $m['nama'] }}</h3>
                        <p class="relative z-[1] text-[11.5px] text-white/55 mt-0.5">{{ $m['peran'] }}</p>

                        {{-- Tagline (dashed divider di atas). --}}
                        <p class="dev-tagline relative z-[1] text-[11.5px] italic text-white/55 leading-relaxed mt-3 pt-3 border-t border-dashed border-white/10 w-full">
                            {!! $m['tagline'] !!}
                        </p>

                        {{-- Ikon sosial ( disembunyikan bila kosong, mis. anggota placeholder baru). --}}
                        @if(!empty($m['socials']))
                            <div class="relative z-[1] flex items-center justify-center gap-2 mt-4">
                                @foreach($m['socials'] as $s)
                                    <a href="{{ $s['url'] }}" target="_blank" rel="noopener"
                                       class="w-[30px] h-[30px] rounded-[9px] bg-white/[.04] border border-white/10 flex items-center justify-center text-white/55 hover:text-[var(--accent)] hover:border-[var(--accent)] hover:-translate-y-0.5 transition"
                                       aria-label="{{ $m['nama'] }} di {{ $s['label'] }}" title="{{ $s['label'] }}"
                                       style="--accent:{{ $m['accent'] }}">
                                        <i class="{{ $s['icon'] }} text-xs"></i>
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </article>
                @endforeach
            </div>

            {{-- "Dibangun dengan …" --}}
            <p class="text-center text-white/50 text-[13px] mt-12">
                Dibangun dengan <i class="fa-solid fa-heart text-[var(--gold)] mx-0.5"></i> untuk
                <strong class="text-[var(--cream)] font-semibold">TSAQIB</strong> &middot; Forum Studi Islam SMAN 1 Bukittinggi
            </p>
        </div>
    </main>

    {{-- Footer bersama: barisan logo instansi + badge Liivo (ter-link ke /credits). --}}
    @include('partials.site-footer')

</body>
</html>
