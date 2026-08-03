<!-- resources/views/landing.blade.php -->
<!DOCTYPE html>
<html lang="id" class="overflow-hidden">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peta Dunia TSAQIB - SMAN 1 Bukittinggi</title>
    @vite('resources/css/app.css')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@600;700;800&family=Inter:wght@400;500;600&family=Manrope:wght@600;700&display=swap" rel="stylesheet">
    <style>
        :root{
            --cream:#F7F5EF;
            --ink:#10140F;
            --gold:#C9A66B;
            --font-display:'Plus Jakarta Sans', sans-serif;
            --font-body:'Inter', sans-serif;
            --font-label:'Manrope', sans-serif;
        }
        *{margin:0;padding:0;box-sizing:border-box;}
        html,body{height:100%;background:#DCEBF2;}
        body{
            font-family:var(--font-body);
            display:flex;
            align-items:center;
            justify-content:center;
            min-height:100vh;
            overflow:hidden;
        }

        .scene{
            position:relative;
            width:100%;
            max-width:2560px;
            aspect-ratio: 2560 / 1440;
            margin:auto;
            overflow:hidden;
        }

        .layer{
            position:absolute;
            display:block;
            width:100%;
            height:100%;
            object-fit:contain;
        }
        /* z-index eksplisit — urutan render dijamin BELAKANG -> DEPAN,
           nggak lagi cuma andalkan urutan DOM (soalnya animasi transform
           di .enter / .island-float bisa bikin browser tertentu salah stacking
           kalau cuma DOM order yang jadi acuan). */
        .l-sky              { left:0%;      top:0%;      width:100%;    height:100%;   z-index:1; }
        .l-clouds-mist      { left:0%;      top:65.486%; width:100%;    height:34.514%; z-index:2; }
        .l-clouds-near      { left:3.828%;  top:41.458%; width:43.477%; height:50.972%; z-index:3; }
        .l-buildings-b      { left:0%;      top:0%;      width:98.984%; height:90.486%; z-index:4; }
        .l-building-c       { left:26.641%; top:7.431%;  width:30.391%; height:79.792%; z-index:5; }
        .l-clouds-far       { left:22.852%; top:0%;      width:77.148%; height:100%;   z-index:6; }
        .l-main-island      { left:10.625%; top:0%;      width:87.578%; height:100%;   z-index:8; }
        .l-foreground-decor { left:18.242%; top:32.222%; width:50.078%; height:46.597%; z-index:9; }
        .l-mosque-decor     { left:0%;      top:0%;      width:83.594%; height:84.653%; z-index:10; }
        .l-building-a       { left:0%;      top:36.667%; width:23.086%; height:63.333%; z-index:12; }

        .enter{
            opacity:0;
            transform:translateY(14px);
            animation:riseIn .9s cubic-bezier(.22,.8,.3,1) forwards;
        }
        @keyframes riseIn{
            from{ opacity:0; transform:translateY(14px); }
            to{ opacity:1; transform:translateY(0); }
        }

        .l-sky          { animation-delay: 0s; }
        .l-clouds-far   { animation-delay: .15s; }
        .l-clouds-near  { animation-delay: .25s; }
        .l-clouds-mist  { animation-delay: .3s; }
        /* .l-building-a   { animation-delay: .4s; }
        .l-buildings-b  { animation-delay: .48s; } */
        /* .l-building-c   { animation-delay: .56s; } */

        /* pulau + semua yang ada di atasnya (masjid, buku, dekorasi) naik-turun BARENG */
        .island-float{
            position:absolute;
            inset:0;
            z-index:7;
            animation: floatIsland 6.5s ease-in-out 2s infinite;
        }
        @keyframes floatIsland{
            0%,100%{ transform:translateY(0); }
            50%{ transform:translateY(-10px); }
        }
        .island-group{ position:absolute; inset:0; }
        .l-main-island      { animation-delay: .7s; }
        .l-mosque-decor     { animation-delay: .85s; }
        .l-foreground-decor { animation-delay: 1s; }

        /* ===== hitbox masjid — posisi diambil dari bounding box kubah aslinya =====
           z-index eksplisit WAJIB di sini, kalau nggak hitbox ini "ketiban"
           gambar mosque-decor/foreground-decor yang juga punya z-index eksplisit
           (elemen tanpa z-index selalu kalah tumpuk dari elemen ber-z-index,
           nggak peduli urutan DOM-nya) — makanya kemarin masjid & buku nggak bisa diklik. */
        .mosque-hotspot{
            position:absolute;
            left:37.11%;
            top:32%;
            width:18.75%;
            height:38%;
            cursor:pointer;
            z-index:15;
            opacity:0;
            animation: fadeInHotspot .6s ease 1.6s forwards;
        }
        @keyframes fadeInHotspot{ to{ opacity:1; } }
        .mosque-hotspot:hover{ filter:drop-shadow(0 0 22px rgba(201,166,107,.6)); }
        .mosque-hotspot:hover ~ .mosque-tooltip{ opacity:1; transform:translate(-50%, -4px); }

        .mosque-tooltip{
            position:absolute;
            left:46.49%;
            top:26%;
            transform:translate(-50%, 4px);
            background:var(--ink);
            color:var(--cream);
            font-family:var(--font-label);
            font-size:clamp(9px, 1.1vw, 13px);
            letter-spacing:.06em;
            text-transform:uppercase;
            padding:6px 12px;
            border-radius:7px;
            white-space:nowrap;
            opacity:0;
            pointer-events:none;
            transition:opacity .25s ease, transform .25s ease;
            z-index:16;
        }

        /* ===== hitbox lambang komunitas / bola merah kacamata — gerbang auth ===== */
        .emblem-hotspot{
            position:absolute;
            left:64.45%;
            top:55.97%;
            width:5.08%;
            height:5.56%;
            cursor:pointer;
            z-index:15;
            opacity:0;
            border-radius:50%;
            animation: fadeInHotspot .6s ease 1.7s forwards;
        }
        .emblem-hotspot:hover{ filter:drop-shadow(0 0 20px rgba(225,29,72,.7)); }
        .emblem-hotspot:hover ~ .emblem-tooltip{ opacity:1; transform:translate(-50%, -4px); }

        .emblem-tooltip{
            position:absolute;
            left:66.99%;
            top:51%;
            transform:translate(-50%, 4px);
            background:var(--ink);
            color:var(--cream);
            font-family:var(--font-label);
            font-size:clamp(9px, 1.1vw, 13px);
            letter-spacing:.06em;
            text-transform:uppercase;
            padding:6px 12px;
            border-radius:7px;
            white-space:nowrap;
            opacity:0;
            pointer-events:none;
            transition:opacity .25s ease, transform .25s ease;
            z-index:16;
        }

        /* ===== hitbox buku — sementara jadi pintu masuk Perpustakaan,
           sampai Pulau Perpustakaan terpisah tersedia sebagai asset sendiri ===== */
        .book-hotspot{
            position:absolute;
            left:20.31%;
            top:40.97%;
            width:17.58%;
            height:15.28%;
            cursor:pointer;
            z-index:15;
            opacity:0;
            animation: fadeInHotspot .6s ease 1.75s forwards;
        }
        .book-hotspot:hover{ filter:drop-shadow(0 0 18px rgba(1,121,95,.55)); }
        .book-hotspot:hover ~ .book-tooltip{ opacity:1; transform:translate(-50%, -4px); }

        .book-tooltip{
            position:absolute;
            left:29.10%;
            top:36%;
            transform:translate(-50%, 4px);
            background:var(--ink);
            color:var(--cream);
            font-family:var(--font-label);
            font-size:clamp(9px, 1.1vw, 13px);
            letter-spacing:.06em;
            text-transform:uppercase;
            padding:6px 12px;
            border-radius:7px;
            white-space:nowrap;
            opacity:0;
            pointer-events:none;
            transition:opacity .25s ease, transform .25s ease;
            z-index:16;
        }

        /* ===== hitbox pohon — ngikutin siluet pohon (ijo+coklat), area abu2 (bangunan/awan
           di belakangnya) dikecualikan lewat clip-path, bukan kotak persegi lagi.
           Membuka prototype Figma pada tab baru. ===== */
        .tree-hotspot{
            position:absolute;
            left:66%;
            top:8%;
            width:34%;
            height:88%;
            cursor:pointer;
            z-index:15;
            opacity:0;
            transition:transform .3s ease, filter .3s ease;
            animation: fadeInHotspot .6s ease 1.8s forwards;
            clip-path: polygon(
                38% 0%, 58% 2%, 74% 8%, 87% 18%,
                95% 30%, 98% 43%, 94% 55%, 84% 64%,
                68% 69%, 60% 100%, 42% 100%, 36% 69%,
                20% 63%, 8% 52%, 3% 38%, 5% 24%,
                14% 12%, 26% 4%
            );
        }
        .tree-hotspot:hover{
            transform:scale(1.02);
            filter:drop-shadow(0 0 20px rgba(1,121,95,.75));
        }
        .tree-hotspot:hover ~ .tree-tooltip{ opacity:1; transform:translate(-50%, -4px); }

        .tree-tooltip{
            position:absolute;
            left:83%;
            top:4%;
            transform:translate(-50%, 4px);
            background:var(--ink);
            color:var(--cream);
            font-family:var(--font-label);
            font-size:clamp(9px, 1.1vw, 13px);
            letter-spacing:.06em;
            text-transform:uppercase;
            padding:6px 12px;
            border-radius:7px;
            white-space:nowrap;
            opacity:0;
            pointer-events:none;
            transition:opacity .25s ease, transform .25s ease;
            z-index:16;
        }

        /* ===== MODE DEBUG: tambahkan ?debug=1 di URL buat lihat area hotspot ===== */
        body.debug-hotspot .mosque-hotspot,
        body.debug-hotspot .emblem-hotspot,
        body.debug-hotspot .book-hotspot{
            opacity:1 !important;
            background:rgba(255,0,0,.25);
            outline:2px dashed red;
        }
        /* tree-hotspot dipisah: sudah berbentuk polygon (bukan kotak),
           jadi outline dilepas biar nggak bikin bingung bentuk aslinya */
        body.debug-hotspot .tree-hotspot{
            opacity:1 !important;
            background:rgba(255,0,0,.35);
        }

        .brand-title{
            position:absolute;
            top:4%;
            left:50%;
            transform:translateX(-50%);
            text-align:center;
            z-index:20;
            opacity:0;
            animation: fadeInHotspot .8s ease 1.9s forwards;
        }
        .brand-eyebrow{
            display:inline-block;
            padding:4px 14px;
            border-radius:999px;
            background:rgba(255,255,255,.85);
            border:1px solid rgba(0,0,0,.08);
            color:#01795F;
            font-family:var(--font-label);
            font-size:clamp(9px,1vw,12px);
            font-weight:700;
            letter-spacing:.04em;
            margin-bottom:6px;
        }
        .brand-h1{
            font-family:var(--font-display);
            font-weight:800;
            font-size:clamp(14px, 2.2vw, 24px);
            color:#1e293b;
        }

        .footer-note{
            position:absolute;
            bottom:2%;
            left:50%;
            transform:translateX(-50%);
            z-index:20;
            font-family:var(--font-label);
            font-size:clamp(8px, .9vw, 10px);
            color:#64748b;
            background:rgba(255,255,255,.8);
            padding:4px 12px;
            border-radius:999px;
            border:1px solid rgba(0,0,0,.06);
            opacity:0;
            animation: fadeInHotspot .8s ease 2.1s forwards;
        }

        @media (prefers-reduced-motion: reduce){
            *{ animation-duration:.01ms !important; animation-iteration-count:1 !important; }
        }
    </style>
</head>
<body class="select-none">

    <div class="scene">

        {{-- <img class="layer l-sky enter" src="{{ asset('assets/landing/sky.png') }}" alt=""> --}}
        <video class="layer l-sky enter" src="{{ asset('assets/landing/video.webm') }}" autoplay loop muted playsinline></video>
        
        <!-- clouds jauh & kabut dasar dulu, tetap di belakang gedung -->
        <img class="layer l-clouds-mist enter" src="{{ asset('assets/landing/clouds-mist.png') }}" alt="">
        {{-- <img class="layer l-clouds-near enter" src="{{ asset('assets/landing/clouds-near.png') }}" alt=""> --}}

        {{-- <img class="layer l-buildings-b enter" src="{{ asset('assets/landing/buildings-b.png') }}" alt="">
        <img class="layer l-building-c enter" src="{{ asset('assets/landing/building-c.png') }}" alt=""> --}}

        <!-- clouds-far ditaruh SETELAH gedung: awan pojok kanan-bawah harus overlap DI DEPAN gedung -->
        <img class="layer l-clouds-far enter" src="{{ asset('assets/landing/clouds-far.png') }}" alt="">

        <div class="brand-title">
            <span class="brand-eyebrow">Labor PAI Digital SMAN 1 Bukittinggi</span>
            <div class="flex justify-center items-center gap-6 md:gap-10">
                <img src="{{ asset('assets/logo-instansi/kemenag.webp') }}" alt="Logo 1" class="h-14 w-14 object-contain">
                <img src="{{ asset('assets/logo-instansi/pendidikan.webp') }}" alt="Logo 2" class="h-14 w-14 object-contain">
                <img src="{{ asset('assets/logo-instansi/sumbar.webp') }}" alt="Logo 3" class="h-14 w-14 object-contain">
                <img src="{{ asset('assets/logo-instansi/smansa.webp') }}" alt="Logo 4" class="h-14 w-14 object-contain">
            </div>
        </div>
        </div>

        <!-- pulau + masjid + buku + dekorasi: satu grup, naik-turun bareng -->
        <div class="island-float">
            <div class="island-group">
                <img class="layer l-main-island enter" src="{{ asset('assets/landing/main-island.png') }}" alt="Pulau utama TSAQIB">
                <img class="layer l-foreground-decor enter" src="{{ asset('assets/landing/foreground-decor.png') }}" alt="">
                <img class="layer l-mosque-decor enter" src="{{ asset('assets/landing/mosque-decor.png') }}" alt="Masjid dan perpustakaan mini">

                <!-- MASJID: Publik, langsung mengarahkan ke Halaman Hub Masjid (Laboratorium PAI & Open Recruitment) -->
                <a class="mosque-hotspot" href="{{ route('hub') }}" title="Laboratorium PAI & Pendaftaran" style="display:block;"></a>
                <div class="mosque-tooltip">Laboratorium PAI & Pendaftaran</div>

                <!-- EMBLEM MASCOT / BOLA MERAH KACAMATA: Gerbang Auth Utama Komunitas -->
                <div class="emblem-hotspot" onclick="handleEmblemClick()" title="Masuk Komunitas TSAQIB"></div>
                <div class="emblem-tooltip">Masuk Komunitas TSAQIB</div>

                <!-- BUKU: publik, langsung ke perpustakaan tanpa auth -->
                <a class="book-hotspot" href="{{ route('perpustakaan') }}" title="Perpustakaan" style="display:block;"></a>
                <div class="book-tooltip">Perpustakaan</div>

                <!-- POHON (ngikutin siluet ijo+coklat, area abu2 dikecualikan): Membuka Prototype Figma pada tab baru -->
                <a class="tree-hotspot" href="https://www.figma.com/proto/1Azmk9c0fapjsTICrk7hU6/Tsaqib-Adv?node-id=5-4&t=O3fg7rE3EBm3cqZ7-0&scaling=min-zoom&content-scaling=fixed&page-id=0%3A1&starting-point-node-id=1%3A2" target="_blank" rel="noopener noreferrer" title="Lihat Prototype TSAQIB" style="display:block;"></a>
                <div class="tree-tooltip">Lihat Prototype TSAQIB</div>
            </div>
        </div>

        <!-- building-a: PALING DEPAN, di luar island-float supaya nggak ikut idle-float pulau,
             tapi tetap render di ATAS pulau (overlap ke island) sesuai reference art -->
        {{-- <img class="layer l-building-a enter" src="{{ asset('assets/landing/building-a.png') }}" alt=""> --}}

        <div class="footer-note">&copy; {{ date('Y') }} TSAQIB • Forum Studi Islam SMAN 1 Bukittinggi</div>

    </div>

    <script>
        // Logic Auth Pindah ke Hitbox Emblem Komunitas (Bola Merah Kacamata)
        function handleEmblemClick() {
            @auth
                @if(Auth::user()->selected_community)
                    window.location.href = "{{ route('komunitas') }}";
                @else
                    window.location.href = "{{ route('select-role') }}";
                @endif
            @else
                window.location.href = "{{ route('login') }}";
            @endauth
        }

        // Mode debug: buka ?debug=1 di URL untuk lihat area hotspot (merah)
        if (new URLSearchParams(window.location.search).get('debug') === '1') {
            document.body.classList.add('debug-hotspot');
        }
    </script>

</body>
</html>