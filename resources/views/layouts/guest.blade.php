<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}?v=2">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts: Plus Jakarta Sans (satu font untuk seluruh situs) -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- FontAwesome (ikon di dalam input auth) — non-render-blocking, sama seperti partials/theme-head -->
        <link rel="preload" as="style" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" onload="this.onload=null;this.rel='stylesheet'">
        <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"></noscript>

        <!-- Scripts. resources/css/app.css sudah menempel gradient gelap + pola girih
             global pada <body> (sama seperti halaman lain), jadi background site-wide
             otomatis muncul di sini. -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            /* ===== Halaman auth: tema gelap TSAQIB + responsif mobile-first =====
               Wrapper lama Breeze (bg-gray-100) dihilangkan, diganti .auth-page.
               Kartu semi-transparan + override warna komponen Breeze di-scope ke
               .auth-card. Komponen bawaan memakai kelas terang (text-gray-700,
               border-gray-300, bg-gray-800, focus:ring-indigo-500) yang tak bisa
               ditimpa lewat atribut class — $attributes->merge menumpuk — jadi
               ditimpa di sini. */

            /* Layout halaman — mobile-first: content dari atas (bukan dipaksa center,
               supaya tidak terpotong di tepi atas saat konten tinggi), lalu center
               vertikal di tablet/desktop. Padding samping 16px di mobile. */
            .auth-page{
                width:100%;
                box-sizing:border-box;
                min-height:100vh;
                min-height:100dvh;                 /* ikuti viewport dinamis (address-bar mobile) */
                display:flex; flex-direction:column;
                align-items:center;
                justify-content:flex-start;
                padding:1.25rem 1rem 2.5rem;       /* samping 16px; atas longgar, bawah ruang scroll */
                overflow-x:hidden;                 /* cegah horizontal scroll */
            }
            @media (min-width:640px){              /* tablet/desktop: center vertikal */
                .auth-page{ justify-content:center; padding:2rem 1rem 3rem; }
            }

            /* Link "Kembali ke beranda" — melayang di pojok kiri-bawah (fixed).
               Mobile: ikon panah saja; ≥640px: panah + teks. */
            .auth-back{
                position:fixed; left:1rem; bottom:1rem; z-index:30;
                display:inline-flex; align-items:center; gap:.4rem;
                min-height:40px; padding:.5rem .85rem;
                color:#C0DD97; font-size:13px; font-weight:600;
                border:1px solid #1a3630; border-radius:9999px;
                background:rgba(15,38,33,.7);
                -webkit-backdrop-filter:blur(8px); backdrop-filter:blur(8px);
                text-decoration:none;
                transition:background .2s ease, color .2s ease;
            }
            .auth-back:hover{ background:rgba(29,158,117,.18); color:#F7F5EF; }
            .auth-back-text{ display:none; }                      /* mobile: panah saja */
            @media (min-width:640px){ .auth-back-text{ display:inline; } }

            /* Logo — ukuran kanonik (sama persis dgn navbar: 40px → 44px di ≥640px)
               supaya konsisten lintas halaman. max-width mencegah overflow di layar sempit. */
            .auth-logo{ height:40px; width:auto; max-width:70vw; display:block; }
            @media (min-width:640px){ .auth-logo{ height:44px; } }

            /* Kartu — full-width di mobile, max-w-md (28rem) di ≥640px. box-sizing
               memastikan padding px-* dihitung di dalam lebar, tidak menyebabkan overflow. */
            .auth-card{
                width:100%;
                box-sizing:border-box;
                background:rgba(15,38,33,0.7);
                border:1px solid #1a3630;
                border-radius:16px;
                -webkit-backdrop-filter:blur(10px);
                backdrop-filter:blur(10px);
                box-shadow:0 24px 60px -24px rgba(0,0,0,.65);
            }

            /* Label input → hijau muda #C0DD97 */
            .auth-card label{ color:#C0DD97; font-weight:600; }

            /* Field input → gelap #08140f, border samar #1a3630, full-width */
            .auth-card input[type=text],
            .auth-card input[type=email],
            .auth-card input[type=password]{
                width:100%;
                padding:.7rem .75rem .7rem 2.5rem;   /* lebih lega (tinggi) + ruang ikon kiri */
                background-color:#08140f !important;
                border:1px solid #1a3630 !important;
                color:#F7F5EF !important;
                border-radius:10px;
            }
            .auth-card input[type=text]::placeholder,
            .auth-card input[type=email]::placeholder,
            .auth-card input[type=password]::placeholder{ color:rgba(247,245,239,.35); }
            .auth-card input[type=text]:focus,
            .auth-card input[type=email]:focus,
            .auth-card input[type=password]:focus{
                outline:none;
                border-color:#1D9E75 !important;
                background-color:#0a1c18 !important;
                box-shadow:0 0 0 1px rgba(29,158,117,.45) !important;
            }

            /* Field dengan toggle show/hide → ruang kanan untuk ikon mata.
               Diclass (.pw-field), bukan [type=password], agar padding tak bergeser
               saat type dibalik password↔text oleh toggle. */
            .auth-card input.pw-field{ padding-right:2.5rem; }

            /* Checkbox "Remember me" */
            .auth-card input[type=checkbox]{ accent-color:#1D9E75; }

            /* Tombol submit → hijau brand #1D9E75, pil, mirip "Masuk" di navbar.
               Lebar dikendalikan dari kelas w-full (mobile) / sm:w-auto (≥640px)
               di view login/register. */
            .auth-card button[type=submit]{
                background-color:#1D9E75 !important;
                color:#fff !important;
                border:none !important;
                border-radius:9999px !important;
                text-transform:none;
                letter-spacing:.02em;
                font-weight:700;
                padding:.625rem 1.5rem;
                box-shadow:0 10px 24px -10px rgba(29,158,117,.7) !important;
            }
            .auth-card button[type=submit]:hover{ background-color:#1aa882 !important; }
            .auth-card button[type=submit]:focus{ outline:none; box-shadow:0 0 0 2px rgba(29,158,117,.5) !important; }

            @media (prefers-reduced-motion: reduce){
                *{ transition-duration:.01ms !important; }
            }
        </style>
    </head>
    <body class="font-sans text-[#F7F5EF] antialiased overflow-x-hidden">
        <div class="auth-page">
            <a href="/" class="auth-back" aria-label="Kembali ke beranda">
                <span aria-hidden="true">&larr;</span>
                <span class="auth-back-text">Kembali ke beranda</span>
            </a>

            <a href="/" aria-label="Beranda TSAQIB">
                <img src="{{ asset('images/icon/tsaqib-media.svg.png') }}" alt="TSAQIB" class="auth-logo" />
            </a>

            <div class="auth-card w-full sm:max-w-md mt-6 px-6 py-8 sm:px-8">
                {{ $slot }}
            </div>
        </div>

        {{-- Show/hide password toggle (generic: any .pw-toggle via data-toggle) --}}
        <script>
        (function () {
            document.querySelectorAll('.pw-toggle').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    const input = document.getElementById(btn.getAttribute('data-toggle'));
                    if (!input) return;
                    const show = input.type === 'password';
                    input.type = show ? 'text' : 'password';
                    btn.setAttribute('aria-pressed', show ? 'true' : 'false');
                    btn.setAttribute('aria-label', show ? 'Sembunyikan password' : 'Tampilkan password');
                    const icon = btn.querySelector('i');
                    if (icon) {
                        icon.classList.toggle('fa-eye', !show);
                        icon.classList.toggle('fa-eye-slash', show);
                    }
                });
            });
        })();
        </script>
    </body>
</html>
