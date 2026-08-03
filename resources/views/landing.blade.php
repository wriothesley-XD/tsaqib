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
        :root {
            --cream: #F7F5EF;
            --ink: #10140F;
            --gold: #C9A66B;
            --font-display: 'Plus Jakarta Sans', sans-serif;
            --font-body: 'Inter', sans-serif;
            --font-label: 'Manrope', sans-serif;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        html, body { 
            height: 100%; 
            width: 100%; 
            background: #DCEBF2; 
            overflow: hidden; 
        }

        body {
            font-family: var(--font-body);
        }

        .scene {
            position: relative;
            width: 100vw;
            height: 100vh;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .layer {
            position: absolute;
            display: block;
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        /* Background & Clouds Positioning */
        .l-sky         { left: 0; top: 0; width: 100%; height: 100%; object-fit: cover; z-index: 1; }
        .l-clouds-mist { left: 0; top: 65%; width: 100%; height: 35%; z-index: 2; object-fit: cover; }
        .l-clouds-near { left: 0; top: 40%; width: 45%; height: 55%; z-index: 3; }
        .l-clouds-far  { left: 20%; top: 0; width: 80%; height: 100%; z-index: 4; }

        /* Initial Animations */
        .enter {
            opacity: 0;
            transform: translateY(14px);
            animation: riseIn .9s cubic-bezier(.22, .8, .3, 1) forwards;
        }
        @keyframes riseIn {
            from { opacity: 0; transform: translateY(14px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .l-sky         { animation-delay: 0s; }
        .l-clouds-far  { animation-delay: .15s; }
        .l-clouds-near { animation-delay: .25s; }
        .l-clouds-mist { animation-delay: .3s; }

        /* Responsive Island Container */
        .island-wrapper {
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            width: 100vw; 
            max-width: 1600px;
            aspect-ratio: 16 / 9;
            z-index: 10;
            pointer-events: none; /* Allows clicks to pass through empty spaces */
        }

        @media (max-width: 767px) {
            .island-wrapper { width: 280vw; } /* Mobile */
        }
        @media (min-width: 768px) and (max-width: 1024px) {
            .island-wrapper { width: 150vw; } /* Tablet */
        }

        .island-float {
            position: absolute;
            inset: 0;
            animation: floatIsland 6.5s ease-in-out 2s infinite;
        }

        @keyframes floatIsland {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .island-group { 
            position: absolute; 
            inset: 0; 
            pointer-events: auto; /* Re-enable clicks on the island content */
        }

        /* Island Layers */
        .l-main-island      { left: 0; top: 0; z-index: 1; animation-delay: .7s; }
        .l-mosque-decor     { left: 0; top: 0; z-index: 2; animation-delay: .85s; }
        .l-foreground-decor { left: 0; top: 0; z-index: 3; animation-delay: 1s; }

        /* ===== HOTSPOTS & TOOLTIPS ===== */
        .hotspot {
            position: absolute;
            cursor: pointer;
            z-index: 15;
            opacity: 0;
            display: block;
            animation: fadeInHotspot .6s ease 1.6s forwards;
            -webkit-tap-highlight-color: transparent;
        }
        
        @keyframes fadeInHotspot { to { opacity: 1; } }

        .tooltip {
            position: absolute;
            transform: translate(-50%, calc(-100% + 4px));
            background: var(--ink);
            color: var(--cream);
            font-family: var(--font-label);
            font-size: clamp(10px, 1.2vw, 13px);
            letter-spacing: .06em;
            text-transform: uppercase;
            padding: 6px 12px;
            border-radius: 7px;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: opacity .25s ease, transform .25s ease;
            z-index: 16;
        }

        .hotspot:hover ~ .tooltip {
            opacity: 1;
            transform: translate(-50%, calc(-100% - 4px));
        }

        /* 1. Masjid */
        .hotspot-masjid { left: 38%; top: 30%; width: 16%; height: 35%; }
        .hotspot-masjid:hover { filter: drop-shadow(0 0 22px rgba(201,166,107,.6)); }
        .tooltip-masjid { left: 46%; top: 30%; }

        /* 2. Pohon */
        .hotspot-pohon {
            left: 68%; top: 10%; width: 30%; height: 85%;
            transition: transform .3s ease, filter .3s ease;
            clip-path: polygon(
                38% 0%, 58% 2%, 74% 8%, 87% 18%,
                95% 30%, 98% 43%, 94% 55%, 84% 64%,
                68% 69%, 60% 100%, 42% 100%, 36% 69%,
                20% 63%, 8% 52%, 3% 38%, 5% 24%,
                14% 12%, 26% 4%
            );
        }
        .hotspot-pohon:hover {
            transform: scale(1.03);
            filter: drop-shadow(0 0 20px rgba(1,121,95,.75));
        }
        .tooltip-pohon { left: 83%; top: 10%; }

        /* 3. Buku */
        .hotspot-buku { left: 22%; top: 42%; width: 15%; height: 14%; }
        .hotspot-buku:hover { filter: drop-shadow(0 0 18px rgba(1,121,95,.55)); }
        .tooltip-buku { left: 29.5%; top: 42%; }

        /* 4. Emblem */
        .hotspot-emblem { left: 62%; top: 56%; width: 5%; height: 6%; border-radius: 50%; }
        .hotspot-emblem:hover { filter: drop-shadow(0 0 20px rgba(225,29,72,.7)); }
        .tooltip-emblem { left: 64.5%; top: 56%; }

        /* 5. Logo FSI (NEW) */
        .hotspot-fsi { left: 15%; top: 62%; width: 8%; height: 10%; border-radius: 50%; }
        .hotspot-fsi:hover { filter: drop-shadow(0 0 15px rgba(255,255,255,.5)); }
        .tooltip-fsi { left: 19%; top: 62%; }

        /* 6. Instagram (NEW) */
        .hotspot-ig { left: 52%; top: 68%; width: 4%; height: 5%; border-radius: 50%; }
        .hotspot-ig:hover { filter: drop-shadow(0 0 15px rgba(225,48,108,.6)); }
        .tooltip-ig { left: 54%; top: 68%; }

        /* 7. TikTok (NEW) */
        .hotspot-tiktok { left: 57%; top: 69%; width: 4%; height: 5%; border-radius: 50%; }
        .hotspot-tiktok:hover { filter: drop-shadow(0 0 15px rgba(255,255,255,.6)); }
        .tooltip-tiktok { left: 59%; top: 69%; }

        /* 8. Facebook (NEW) */
        .hotspot-fb { left: 47%; top: 69%; width: 4%; height: 5%; border-radius: 50%; }
        .hotspot-fb:hover { filter: drop-shadow(0 0 15px rgba(24,119,242,.6)); }
        .tooltip-fb { left: 49%; top: 69%; }

        /* Responsive tooltips adjustments to prevent edge overflow */
        @media (max-width: 767px) {
            .tooltip-pohon { transform: translate(-80%, calc(-100% + 4px)); }
            .hotspot-pohon:hover ~ .tooltip-pohon { transform: translate(-80%, calc(-100% - 4px)); }
            
            .tooltip-fsi { transform: translate(-20%, calc(-100% + 4px)); }
            .hotspot-fsi:hover ~ .tooltip-fsi { transform: translate(-20%, calc(-100% - 4px)); }
        }

        /* ===== BRAND & FOOTER ===== */
        .brand-title {
            position: absolute;
            top: 4%;
            left: 50%;
            transform: translateX(-50%);
            text-align: center;
            z-index: 20;
            opacity: 0;
            animation: fadeInHotspot .8s ease 1.9s forwards;
            width: 100%;
        }

        .brand-eyebrow {
            display: inline-block;
            padding: 4px 14px;
            border-radius: 999px;
            background: rgba(255,255,255,.85);
            border: 1px solid rgba(0,0,0,.08);
            color: #01795F;
            font-family: var(--font-label);
            font-size: clamp(9px, 1vw, 12px);
            font-weight: 700;
            letter-spacing: .04em;
            margin-bottom: 6px;
        }

        .footer-note {
            position: absolute;
            bottom: 2%;
            left: 50%;
            transform: translateX(-50%);
            z-index: 20;
            font-family: var(--font-label);
            font-size: clamp(8px, .9vw, 10px);
            color: #64748b;
            background: rgba(255,255,255,.8);
            padding: 4px 12px;
            border-radius: 999px;
            border: 1px solid rgba(0,0,0,.06);
            opacity: 0;
            animation: fadeInHotspot .8s ease 2.1s forwards;
            white-space: nowrap;
        }

        @media (max-width: 767px) {
            .brand-title { top: 2%; }
            .footer-note { bottom: 3%; }
        }

        /* ===== DEBUG MODE ===== */
        body.debug-hotspot .hotspot {
            opacity: 0.6 !important;
            background: rgba(255, 0, 0, 0.4);
            outline: 2px dashed red;
        }
        body.debug-hotspot .hotspot-pohon {
            background: rgba(0, 255, 0, 0.3);
            outline: none;
        }

        @media (prefers-reduced-motion: reduce) {
            * { animation-duration: .01ms !important; animation-iteration-count: 1 !important; }
        }
    </style>
</head>
<body class="select-none">

    <div class="scene">

        <!-- Background Video -->
        <video class="layer l-sky enter" src="{{ asset('assets/landing/video.webm') }}" autoplay loop muted playsinline></video>
        
        <!-- Clouds -->
        <img class="layer l-clouds-mist enter" src="{{ asset('assets/landing/clouds-mist.png') }}" alt="">
        <img class="layer l-clouds-far enter" src="{{ asset('assets/landing/clouds-far.png') }}" alt="">
        <img class="layer l-clouds-near enter" src="{{ asset('assets/landing/clouds-near.png') }}" alt="">

        <!-- Brand Title -->
        <div class="brand-title">
            <span class="brand-eyebrow">Labor PAI Digital SMAN 1 Bukittinggi</span>
            <div class="flex justify-center items-center gap-6 md:gap-10">
                <img src="{{ asset('assets/logo-instansi/kemenag.webp') }}" alt="Kemenag" class="h-14 w-14 object-contain">
                <img src="{{ asset('assets/logo-instansi/pendidikan.webp') }}" alt="Pendidikan" class="h-14 w-14 object-contain">
                <img src="{{ asset('assets/logo-instansi/sumbar.webp') }}" alt="Sumbar" class="h-14 w-14 object-contain">
                <img src="{{ asset('assets/logo-instansi/smansa.webp') }}" alt="Smansa" class="h-14 w-14 object-contain">
            </div>
        </div>

        <!-- Floating Island Composition -->
        <div class="island-wrapper">
            <div class="island-float">
                <div class="island-group">
                    
                    <!-- Images -->
                    <img class="layer l-main-island enter" src="{{ asset('assets/landing/main-island.png') }}" alt="Pulau utama TSAQIB">
                    <img class="layer l-mosque-decor enter" src="{{ asset('assets/landing/mosque-decor.png') }}" alt="Masjid dan perpustakaan mini">
                    <img class="layer l-foreground-decor enter" src="{{ asset('assets/landing/foreground-decor.png') }}" alt="">

                    <!-- HOTSPOTS -->
                    
                    <!-- Masjid -->
                    <a class="hotspot hotspot-masjid" href="{{ route('hub') }}" title="Laboratorium PAI & Pendaftaran"></a>
                    <div class="tooltip tooltip-masjid">Laboratorium PAI & Pendaftaran</div>

                    <!-- Emblem -->
                    <div class="hotspot hotspot-emblem" onclick="handleEmblemClick()" title="Masuk Komunitas TSAQIB"></div>
                    <div class="tooltip tooltip-emblem">Masuk Komunitas TSAQIB</div>

                    <!-- Buku -->
                    <a class="hotspot hotspot-buku" href="{{ route('perpustakaan') }}" title="Perpustakaan"></a>
                    <div class="tooltip tooltip-buku">Perpustakaan</div>

                    <!-- Pohon -->
                    <a class="hotspot hotspot-pohon" href="https://www.figma.com/proto/1Azmk9c0fapjsTICrk7hU6/Tsaqib-Adv?node-id=5-4&t=O3fg7rE3EBm3cqZ7-0&scaling=min-zoom&content-scaling=fixed&page-id=0%3A1&starting-point-node-id=1%3A2" target="_blank" rel="noopener noreferrer" title="Lihat Prototype TSAQIB"></a>
                    <div class="tooltip tooltip-pohon">Lihat Prototype TSAQIB</div>

                    <!-- Logo FSI (NEW) -->
                    <a class="hotspot hotspot-fsi" href="{{ route('hub') }}" title="Forum Studi Islam"></a>
                    <div class="tooltip tooltip-fsi">Forum Studi Islam</div>

                    <!-- Social Media (NEW) -->
                    <a class="hotspot hotspot-fb" href="https://facebook.com/fsi.smanbu" target="_blank" rel="noopener noreferrer" title="Facebook"></a>
                    <div class="tooltip tooltip-fb">Facebook</div>

                    <a class="hotspot hotspot-ig" href="https://instagram.com/fsi_smanbu" target="_blank" rel="noopener noreferrer" title="Instagram"></a>
                    <div class="tooltip tooltip-ig">Instagram</div>

                    <a class="hotspot hotspot-tiktok" href="https://tiktok.com/@fsi_smanbu" target="_blank" rel="noopener noreferrer" title="TikTok"></a>
                    <div class="tooltip tooltip-tiktok">TikTok</div>

                </div>
            </div>
        </div>

        <!-- Footer Note -->
        <div class="footer-note">&copy; {{ date('Y') }} TSAQIB • Forum Studi Islam SMAN 1 Bukittinggi</div>

    </div>

    <script>
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

        // Mode debug: buka ?debug=1 di URL
        if (new URLSearchParams(window.location.search).get('debug') === '1') {
            document.body.classList.add('debug-hotspot');
        }
    </script>

</body>
</html>