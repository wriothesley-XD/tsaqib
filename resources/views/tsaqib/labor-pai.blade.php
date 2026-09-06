@php
    $pageTitle = 'Laboratorium PAI - FSI SMAN 1 Bukittinggi';
    $monevUrl = !empty($monevPdf) ? asset('storage/' . $monevPdf) : asset('assets/documents/monev-internal-pemerintah-daerah.pdf');
    $profilBukuFileUrl = !empty($profilBukuPdf) ? asset('storage/' . $profilBukuPdf) : null;
    $pembinaImgSrc = !empty($strukturPembinaImg) ? asset('storage/' . $strukturPembinaImg) : asset('images/struktur.webp');
    $siswaImgSrc = !empty($strukturSiswaImg) ? asset('storage/' . $strukturSiswaImg) : asset('images/kepengurusan.webp');
@endphp

@push('styles')
    /* ===== Laboratorium PAI: aksen kartu + carousel horizontal (mobile) ===== */

    @media (max-width: 1023px){
        .lp-cards > .tsaqib-card:nth-child(odd)  { border-left: 3px solid var(--green); }
        .lp-cards > .tsaqib-card:nth-child(even) { border-left: 3px solid var(--gold); }
    }

    .lp-carousel{
        position:relative;
        overflow:hidden;
        padding:.5rem 0;
        -webkit-mask-image:linear-gradient(to right, transparent, #000 5%, #000 95%, transparent);
        mask-image:linear-gradient(to right, transparent, #000 5%, #000 95%, transparent);
    }
    .lp-track{
        position:relative;
        display:flex;
        width:max-content;
        --lp-duration:40s;
        animation:lp-loop var(--lp-duration) linear infinite;
        touch-action:pan-y;
        user-select:none;
        cursor:grab;
    }
    .lp-track.is-dragging{ cursor:grabbing; animation-play-state:paused; }
    .lp-track > .lp-card{
        flex:0 0 auto;
        width:min(80vw, 300px);
        margin-right:1rem;
    }
    @keyframes lp-loop{
        from{ transform:translateX(0); }
        to  { transform:translateX(-50%); }
    }

    .lp-card{
        position:relative; overflow:hidden; padding:1.75rem;
        background:linear-gradient(160deg, rgba(247,245,239,.06), rgba(247,245,239,.02));
    }
    .lp-card-deco{
        position:absolute; top:-.5rem; right:-.4rem;
        font-size:5.5rem; line-height:1; color:var(--cream);
        opacity:.05; pointer-events:none;
    }
    .lp-badge{
        display:flex; align-items:center; justify-content:center;
        width:2.75rem; height:2.75rem; border-radius:.85rem;
        font-size:1.05rem; color:var(--cream); margin-bottom:1rem;
        box-shadow:0 6px 18px -6px rgba(0,0,0,.55);
    }
    .lp-badge-green{ background:linear-gradient(140deg, var(--green), var(--green-dark)); }
    .lp-badge-gold{ background:linear-gradient(140deg, var(--gold), #a9893f); color:var(--ink); }
    .lp-card-title{
        font-family:'Plus Jakarta Sans',sans-serif; font-weight:800;
        font-size:1.05rem; line-height:1.3; color:var(--cream); margin-bottom:.5rem;
    }
    .lp-card-body{ font-size:.8rem; line-height:1.6; color:rgba(247,245,239,.62); }
    .lp-card-body strong{ color:var(--cream); font-weight:700; }
    .lp-checklist{ margin-top:.85rem; display:flex; flex-direction:column; gap:.5rem; }
    .lp-checklist li{ display:flex; align-items:flex-start; gap:.5rem; font-size:.78rem; color:rgba(247,245,239,.62); }
    .lp-checklist i{ color:#3fd6b0; font-size:.85rem; margin-top:.1rem; }

    @media (min-width:1024px){
        .lp-carousel{ overflow:visible; -webkit-mask-image:none; mask-image:none; padding:0; }
        .lp-track{
            display:grid;
            grid-template-columns:repeat(3,1fr);
            gap:1.5rem;
            width:auto;
            animation:none;
            transform:none !important;
            touch-action:auto;
            cursor:default;
        }
        .lp-track > .lp-card{ width:auto; margin-right:0; }
        .lp-track > .lp-card.is-clone{ display:none; }
    }

    /* Flipbook Facade & Reader Styles */
    .lp-flipbook-wrap{
        position:relative; width:100%;
        border-radius:.85rem; overflow:hidden;
        background:#10140f;
        box-shadow:0 12px 30px -12px rgba(0,0,0,.6);
    }
    .lp-flipbook-wrap iframe{
        display:block; width:100%;
        height:clamp(420px, 58vw, 560px);
    }
    .lp-flipbook-facade{
        position:relative; width:100%; height:420px;
        cursor:pointer; overflow:hidden;
        background:
            radial-gradient(circle at 30% 20%, rgba(1,121,95,.35), transparent 60%),
            linear-gradient(135deg, #0a4a3a 0%, #10140f 100%);
        display:flex; align-items:center; justify-content:center;
    }
    .lp-flipbook-facade::after{
        content:''; position:absolute; inset:0;
        background-image:repeating-linear-gradient(90deg, rgba(247,245,239,.04) 0 2px, transparent 2px 44px);
        pointer-events:none;
    }
    .lp-flipbook-facade:hover .lp-facade-play{ transform:scale(1.08); background:var(--gold); color:var(--ink); }
    .lp-facade-play{
        position:relative; z-index:2;
        width:5.5rem; height:5.5rem; border-radius:999px;
        display:flex; flex-direction:column; align-items:center; justify-content:center; gap:.15rem;
        background:rgba(247,245,239,.12); color:var(--cream);
        border:1px solid rgba(247,245,239,.25);
        backdrop-filter:blur(4px);
        transition:transform .2s ease, background .2s ease, color .2s ease;
    }
    .lp-facade-play i{ font-size:1.5rem; }
    .lp-facade-play span{ font-size:10px; font-weight:700; letter-spacing:.04em; }
    .lp-facade-hint{
        position:absolute; bottom:1rem; left:0; right:0; z-index:2;
        text-align:center; font-size:11px; color:rgba(247,245,239,.6); font-weight:600;
    }
    .lp-facade-cover{
        position:absolute; inset:0; width:100%; height:100%;
        object-fit:cover; object-position:center;
        z-index:0;
    }
    .lp-flipbook-facade.has-cover::before{
        content:''; position:absolute; inset:0; z-index:1; pointer-events:none;
        background:
            linear-gradient(180deg, rgba(16,20,15,.15) 0%, rgba(16,20,15,.55) 70%, rgba(16,20,15,.78) 100%);
    }
    .lp-flipbook-facade.has-cover::after{ display:none; }
    .lp-flipbook-facade.has-cover{
        border:2px solid color-mix(in srgb, var(--gold) 65%, transparent);
        background:#10140f;
        box-shadow:
            0 2px 0 rgba(247,245,239,.06),
            0 4px 0 rgba(247,245,239,.04),
            0 12px 30px -12px rgba(0,0,0,.6);
    }

    /* Tab switcher document active states */
    .doc-tab.is-active{
        background:rgba(201,166,107,.15);
        border-color:var(--gold);
        color:var(--gold);
    }
    .doc-tab.is-active .doc-tab-indicator{
        background:var(--gold);
    }
@endpush

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    @include('partials.theme-head')
</head>
<body class="text-[var(--cream)] font-sans antialiased min-h-screen flex flex-col">

    <!-- Unified TSAQIB Navbar -->
    @include('partials.navbar')

    <main class="flex-1 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14 space-y-10 w-full">

        <!-- Title Banner -->
        <x-page-header
            eyebrow="Fasilitas & Karakter Rabbani"
            eyebrow-icon="fa-solid fa-flask"
            title="Laboratorium PAI <span class='text-[var(--gold)]'>SMAN 1 Bukittinggi</span>"
            subtitle="Pusat riset, praktikum ibadah, dan pembinaan karakter Pendidikan Agama Islam SMAN 1 Bukittinggi." />

        <!-- Sub-navigation -->
        @include('tsaqib._labor-subnav', ['active' => 'ikhtisar'])

        <!-- 1. SEJARAH SINGKAT, VISI, & 6 PILAR PENGELOLAAN -->
        <div id="profil" class="lp-carousel">
            <div class="lp-track lp-cards">

                <!-- Sejarah Singkat -->
                <div class="tsaqib-card lp-card">
                    <i class="fa-solid fa-clock-rotate-left lp-card-deco" aria-hidden="true"></i>
                    <div class="lp-badge lp-badge-green"><i class="fa-solid fa-clock-rotate-left"></i></div>
                    <h3 class="lp-card-title">Sejarah Singkat</h3>
                    <p class="lp-card-body">Bukan sekadar ruang fisik, Labor PAI adalah pusat <strong>pembinaan karakter</strong>, penguatan akhlak mulia, serta pembiasaan nilai-nilai keislaman dalam kehidupan sehari-hari peserta didik.</p>
                </div>

                <!-- Visi dan Misi -->
                <div class="tsaqib-card lp-card">
                    <i class="fa-solid fa-eye lp-card-deco" aria-hidden="true"></i>
                    <div class="lp-badge lp-badge-gold"><i class="fa-solid fa-eye"></i></div>
                    <h3 class="lp-card-title">Visi dan Misi</h3>
                    <p class="lp-card-body">Menjadi pusat <strong>praktikum keilmuan Islam</strong> dan laboratorium karakter siswa yang unggul, beriman, dan berakhlak mulia.</p>
                    <ul class="lp-checklist">
                        <li><i class="fa-solid fa-square-check"></i><span>Memfasilitasi modul praktikum ibadah siswa.</span></li>
                        <li><i class="fa-solid fa-square-check"></i><span>Mengembangkan media syiar &amp; keilmuan Islam.</span></li>
                        <li><i class="fa-solid fa-square-check"></i><span>Membangun ukhuwah &amp; kepemimpinan Rabbani.</span></li>
                    </ul>
                </div>

                <!-- Legalitas & Struktur Organisasi -->
                <div class="tsaqib-card lp-card">
                    <i class="fa-solid fa-file-contract lp-card-deco" aria-hidden="true"></i>
                    <div class="lp-badge lp-badge-green"><i class="fa-solid fa-file-contract"></i></div>
                    <h3 class="lp-card-title">Legalitas &amp; Struktur Organisasi</h3>
                    <p class="lp-card-body">Dasar <strong>Surat Keputusan (SK)</strong> yang mengatur pembagian tugas, tanggung jawab, dan wewenang setiap personel agar bekerja secara efektif, terukur, dan sesuai ketentuan.</p>
                </div>

                <!-- Perencanaan & Regulasi Operasional -->
                <div class="tsaqib-card lp-card">
                    <i class="fa-solid fa-clipboard-list lp-card-deco" aria-hidden="true"></i>
                    <div class="lp-badge lp-badge-gold"><i class="fa-solid fa-clipboard-list"></i></div>
                    <h3 class="lp-card-title">Perencanaan &amp; Regulasi Operasional</h3>
                    <p class="lp-card-body">Mencakup penyusunan <strong>kebijakan</strong>, <strong>SOP</strong>, tata kelola layanan, pemanfaatan teknologi, dan mekanisme evaluasi untuk operasional yang aman dan berkelanjutan.</p>
                </div>

                <!-- Pelaksanaan Pemanfaatan -->
                <div class="tsaqib-card lp-card">
                    <i class="fa-solid fa-laptop lp-card-deco" aria-hidden="true"></i>
                    <div class="lp-badge lp-badge-green"><i class="fa-solid fa-laptop"></i></div>
                    <h3 class="lp-card-title">Pelaksanaan Pemanfaatan</h3>
                    <p class="lp-card-body">Pusat <strong>pembelajaran</strong> dan pengembangan kompetensi bagi siswa, guru PAI, KKG, serta MGMP PAI melalui sumber belajar digital dan kolaborasi berbasis teknologi.</p>
                </div>

                <!-- Sarana Prasarana & Inventaris -->
                <div class="tsaqib-card lp-card">
                    <i class="fa-solid fa-boxes-stacked lp-card-deco" aria-hidden="true"></i>
                    <div class="lp-badge lp-badge-gold"><i class="fa-solid fa-boxes-stacked"></i></div>
                    <h3 class="lp-card-title">Sarana Prasarana &amp; Inventaris</h3>
                    <p class="lp-card-body">Inventaris <strong>aset</strong>, perangkat keras, dan sarana prasarana penunjang kegiatan Laboratorium PAI Digital.</p>
                </div>

            </div>
        </div>

        <!-- 2. KHAZANAH DIGITAL LABORATORIUM (DUAL DOCUMENT READER) -->
        <section id="ruang-baca" class="space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
                <div>
                    <span class="eyebrow-pill eyebrow-pill-gold mb-2 inline-flex items-center gap-1.5">
                        <i class="fa-solid fa-book-bookmark text-[10px]"></i>
                        Arsip &amp; Publikasi Resmi
                    </span>
                    <h2 class="text-2xl sm:text-3xl font-display font-bold text-[var(--cream)]">
                        <span class="text-[var(--gold)]">Khazanah Digital Laboratorium</span>
                    </h2>
                    <p class="text-white/60 text-xs sm:text-sm mt-1 max-w-xl">
                        Akses literatur resmi, profil kelembagaan, serta laporan monev internal Laboratorium PAI dalam format digital interaktif.
                    </p>
                </div>

                <!-- Document Switcher Pills -->
                <div class="flex items-center gap-2 p-1.5 rounded-2xl bg-black/40 border border-white/10 shrink-0 self-start sm:self-auto" role="tablist" aria-label="Pilih Dokumen">
                    <button type="button" id="tab-btn-flipbook" onclick="switchDoc('flipbook')"
                            class="doc-tab is-active flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-semibold border border-transparent transition-all cursor-pointer">
                        <span class="doc-tab-indicator w-2 h-2 rounded-full bg-[var(--gold)]"></span>
                        <span>Profil TSAQIB</span>
                    </button>
                    <button type="button" id="tab-btn-pdf" onclick="switchDoc('pdf')"
                            class="doc-tab flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-semibold text-white/60 border border-transparent hover:text-white transition-all cursor-pointer">
                        <span class="doc-tab-indicator w-2 h-2 rounded-full bg-transparent"></span>
                        <span>Monev Internal Pemerintah Daerah</span>
                    </button>
                </div>
            </div>

            <!-- Unified Reader Box -->
            <div id="lp-reader-box" class="tsaqib-card p-4 sm:p-6 lg:p-8">

                <!-- PANE 1: PROFIL TSAQIB (FLIPBOOK / DOKUMEN BUKU) -->
                <div id="pane-flipbook" class="space-y-5">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-white/10">
                        <div class="flex items-center gap-3.5">
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-[var(--gold)] to-[#a9893f] text-[#10140F] flex items-center justify-center text-xl shadow-lg shadow-[var(--gold)]/20 shrink-0">
                                <i class="fa-solid fa-book-open"></i>
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <h3 class="text-lg font-display font-bold text-[var(--cream)]">Profil TSAQIB</h3>
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-[var(--gold)]/15 text-[var(--gold)] border border-[var(--gold)]/30">
                                        {{ $profilBukuFileUrl ? 'Dokumen & Flipbook' : 'Flipbook Interaktif' }}
                                    </span>
                                </div>
                                <p class="text-white/55 text-xs mt-0.5">
                                    Kenali profil lengkap TSAQIB FSI — visi, program kerja tahunan, dan sejarah kepengurusan.
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 shrink-0 self-start sm:self-auto flex-wrap">
                            @if($profilBukuFileUrl)
                                <a href="{{ $profilBukuFileUrl }}" target="_blank" rel="noopener"
                                   class="btn-gold text-xs px-4 py-2">
                                    <i class="fa-solid fa-file-pdf text-[11px]"></i>
                                    <span>Buka PDF Buku</span>
                                </a>
                            @endif
                            <a href="{{ $profilTsaqibUrl }}" target="_blank" rel="noopener"
                               class="btn-outline text-xs px-4 py-2">
                                <span>Buka Layar Penuh</span>
                                <i class="fa-solid fa-arrow-up-right-from-square text-[11px]"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Flipbook Embed / Facade -->
                    <div class="lp-flipbook-wrap" id="lp-flipbook">
                        <button type="button" class="lp-flipbook-facade {{ $coverUrl ? 'has-cover' : '' }}"
                                data-src="{{ $profilTsaqibUrl }}"
                                aria-label="Buka flipbook Profil TSAQIB">
                            @if($coverUrl)
                                <img src="{{ $coverUrl }}" alt="Cover Profil TSAQIB" width="267" height="400" class="lp-facade-cover" loading="lazy">
                            @endif
                            <span class="lp-facade-play">
                                <i class="fa-solid fa-book-open"></i>
                                <span>Buka</span>
                            </span>
                            <span class="lp-facade-hint">Klik untuk memuat flipbook interaktif</span>
                        </button>
                    </div>
                </div>

                <!-- PANE 2: MONEV INTERNAL PEMERINTAH DAERAH (PDF) -->
                <div id="pane-pdf" class="hidden space-y-5">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-white/10">
                        <div class="flex items-center gap-3.5">
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-[#01795F] to-[#3F704D] text-white flex items-center justify-center text-xl shadow-lg shadow-[#01795F]/20 shrink-0">
                                <i class="fa-solid fa-file-pdf"></i>
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <h3 class="text-lg font-display font-bold text-[var(--cream)]">Monev Internal Pemerintah Daerah</h3>
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-[#01795F]/15 text-[#3fd6b0] border border-[#01795F]/30">
                                        Dokumen Resmi PDF
                                    </span>
                                </div>
                                <p class="text-white/55 text-xs mt-0.5">
                                    Dokumen evaluasi berkala, akuntabilitas sarana, dan standar mutu Laboratorium PAI di lingkungan SMAN 1 Bukittinggi.
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 shrink-0 self-start sm:self-auto">
                            <a href="{{ $monevUrl }}" target="_blank" rel="noopener"
                               class="btn-outline text-xs px-4 py-2">
                                <span>Buka di Tab Baru</span>
                                <i class="fa-solid fa-arrow-up-right-from-square text-[11px]"></i>
                            </a>
                            <a href="{{ $monevUrl }}" download
                               class="btn-gold btn-download text-xs px-4 py-2">
                                <i class="fa-solid fa-download text-[11px]"></i>
                                <span>Unduh PDF</span>
                            </a>
                        </div>
                    </div>

                    <!-- PDF Viewer Container -->
                    <div class="rounded-xl overflow-hidden border border-white/15 bg-white shadow-2xl">
                        <iframe src="{{ $monevUrl }}#toolbar=1&navpanes=0"
                                title="Dokumen Monev Internal Pemerintah Daerah"
                                class="w-full h-[520px] sm:h-[600px] bg-[#525659]"
                                loading="lazy"></iframe>
                    </div>

                    <div class="flex items-center justify-between text-xs text-white/50 pt-1">
                        <span><i class="fa-solid fa-shield-halved text-[var(--gold)] mr-1.5"></i>Dokumen Terverifikasi FSI TSAQIB &amp; Sekolah</span>
                        <a href="{{ $monevUrl }}" download class="text-[var(--gold)] hover:underline">
                            Simpan salinan (PDF)
                        </a>
                    </div>
                </div>

            </div>
        </section>

        <!-- 3. INFOGRAFIS STRUKTUR ORGANISASI -->
        <div id="struktur" class="tsaqib-card p-6 sm:p-8">
            <div class="flex items-center space-x-3 mb-6 pb-4 border-b border-white/10">
                <div class="w-10 h-10 rounded-xl bg-[#01795F] text-white flex items-center justify-center text-lg shadow-sm">
                    <i class="fa-solid fa-sitemap"></i>
                </div>
                <div>
                    <h2 class="text-xl font-display font-bold text-[var(--cream)]">Infografis Struktur Organisasi</h2>
                    <p class="text-white/50 text-xs">Struktur Pembina Guru &amp; Pengurus Siswa Laboratorium PAI &amp; TSAQIB</p>
                </div>
            </div>

            <!-- Infographic nodes -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="group relative rounded-xl overflow-hidden border border-white/10 bg-white p-3 shadow-lg">
                    <img src="{{ $pembinaImgSrc }}" alt="Struktur Pembina &amp; Laboratorium PAI" width="800" height="600"
                         class="w-full h-auto rounded-lg object-contain transition-transform duration-300 group-hover:scale-[1.01]"
                         loading="lazy" onerror="this.remove()">
                    <span class="block text-center text-xs font-semibold text-[#10140F]/70 mt-2">Bagan Struktur Pembina &amp; Laboratorium PAI</span>
                </div>
                <div class="group relative rounded-xl overflow-hidden border border-white/10 bg-white p-3 shadow-lg">
                    <img src="{{ $siswaImgSrc }}" alt="Kepengurusan Siswa TSAQIB FSI" width="800" height="600"
                         class="w-full h-auto rounded-lg object-contain transition-transform duration-300 group-hover:scale-[1.01]"
                         loading="lazy" onerror="this.remove()">
                    <span class="block text-center text-xs font-semibold text-[#10140F]/70 mt-2">Bagan Kepengurusan Siswa TSAQIB FSI</span>
                </div>
            </div>
        </div>

    </main>

    <!-- Footer -->
    @include('partials.site-footer')

    {{-- Script Document Switcher & Carousel --}}
    <script>
    function switchDoc(type) {
        var paneFlipbook = document.getElementById('pane-flipbook');
        var panePdf = document.getElementById('pane-pdf');
        var tabFlipbook = document.getElementById('tab-btn-flipbook');
        var tabPdf = document.getElementById('tab-btn-pdf');

        if (type === 'flipbook') {
            paneFlipbook.classList.remove('hidden');
            panePdf.classList.add('hidden');
            tabFlipbook.classList.add('is-active');
            tabFlipbook.classList.remove('text-white/60');
            tabFlipbook.querySelector('.doc-tab-indicator').classList.replace('bg-transparent', 'bg-[var(--gold)]');
            tabPdf.classList.remove('is-active');
            tabPdf.classList.add('text-white/60');
            tabPdf.querySelector('.doc-tab-indicator').classList.replace('bg-[var(--gold)]', 'bg-transparent');
        } else {
            panePdf.classList.remove('hidden');
            paneFlipbook.classList.add('hidden');
            tabPdf.classList.add('is-active');
            tabPdf.classList.remove('text-white/60');
            tabPdf.querySelector('.doc-tab-indicator').classList.replace('bg-transparent', 'bg-[var(--gold)]');
            tabFlipbook.classList.remove('is-active');
            tabFlipbook.classList.add('text-white/60');
            tabFlipbook.querySelector('.doc-tab-indicator').classList.replace('bg-[var(--gold)]', 'bg-transparent');
        }
    }

    (function () {
        var track = document.querySelector('.lp-track');
        if (!track) return;

        var originals = Array.prototype.slice.call(track.children);
        originals.forEach(function (node) {
            var clone = node.cloneNode(true);
            clone.classList.add('is-clone');
            clone.setAttribute('aria-hidden', 'true');
            track.appendChild(clone);
        });

        var cards = Array.prototype.slice.call(track.children);
        var step = 0, half = 0;
        function measure() {
            step = cards.length > 1 ? (cards[1].offsetLeft - cards[0].offsetLeft) : cards[0].offsetWidth;
            half = cards[6] ? cards[6].offsetLeft : step * 6;
        }
        measure();
        window.addEventListener('resize', measure);

        var D = parseFloat(getComputedStyle(track).getPropertyValue('--lp-duration') || '40s') * 1000;

        function wrap(v) { v = v % half; if (v > 0) v -= half; return v; }
        function matrixX(el) {
            var m = getComputedStyle(el).transform;
            if (!m || m === 'none') return 0;
            var a = m.match(/matrix[^(]*\(([^)]+)\)/);
            if (!a) return 0;
            var v = a[1].split(',');
            return parseFloat(v.length === 6 ? v[4] : v[12]);
        }
        function pauseAnim() { track.style.animation = 'none'; void track.offsetWidth; }
        function resumeAnim(fromTx) {
            var p = half ? (-wrap(fromTx) / half) : 0;
            if (p < 0) p = 0; if (p >= 1) p = 0;
            track.style.transition = 'none';
            track.style.transform = '';
            track.style.animation = 'lp-loop ' + (D / 1000) + 's linear infinite';
            track.style.animationDelay = (-p * D) + 'ms';
            track.style.animationPlayState = 'running';
        }

        var armed = false, dragging = false, startX = 0, startY = 0, baseTx = 0, cur = 0;

        function onDown(e) {
            if (window.matchMedia('(min-width:1024px)').matches) return;
            armed = true; dragging = false;
            startX = e.clientX; startY = e.clientY;
            baseTx = matrixX(track); cur = baseTx;
            pauseAnim();
            track.style.transform = 'translateX(' + baseTx + 'px)';
            window.addEventListener('pointermove', onMove);
            window.addEventListener('pointerup', onUp);
            window.addEventListener('pointercancel', onUp);
        }
        function onMove(e) {
            if (!armed) return;
            var dx = e.clientX - startX, dy = e.clientY - startY;
            if (!dragging) {
                if (Math.abs(dx) < 5) return;
                if (Math.abs(dy) > Math.abs(dx)) { resumeAnim(baseTx); armed = false; cleanup(); return; }
                dragging = true; track.classList.add('is-dragging');
            }
            cur = wrap(baseTx + dx);
            track.style.transform = 'translateX(' + cur + 'px)';
            e.preventDefault();
        }
        function onUp() {
            if (!armed) return;
            if (dragging) {
                track.classList.remove('is-dragging');
                resumeAnim(wrap(Math.round(cur / step) * step));
            } else {
                resumeAnim(baseTx);
            }
            armed = false; dragging = false; cleanup();
        }
        function cleanup() {
            window.removeEventListener('pointermove', onMove);
            window.removeEventListener('pointerup', onUp);
            window.removeEventListener('pointercancel', onUp);
        }

        track.addEventListener('pointerdown', onDown);
    })();

    (function () {
        var wrap = document.getElementById('lp-flipbook');
        var facade = wrap ? wrap.querySelector('.lp-flipbook-facade') : null;
        if (! wrap || ! facade) return;

        var src = facade.getAttribute('data-src');
        if (! src) return;

        function loadIframe() {
            var iframe = document.createElement('iframe');
            iframe.setAttribute('allowfullscreen', 'allowfullscreen');
            iframe.setAttribute('allow', 'autoplay; fullscreen; clipboard-write');
            iframe.setAttribute('scrolling', 'no');
            iframe.setAttribute('class', 'fp-iframe');
            iframe.setAttribute('src', src);
            iframe.setAttribute('title', 'Flipbook Profil TSAQIB');
            iframe.setAttribute('loading', 'lazy');
            iframe.style.cssText = 'border:1px solid lightgray; width:100%; height:500px;';
            wrap.innerHTML = '';
            wrap.appendChild(iframe);
        }

        facade.addEventListener('click', loadIframe);
        facade.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); loadIframe(); }
        });
    })();
    </script>

</body>
</html>
