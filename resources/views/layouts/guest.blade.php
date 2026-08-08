<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts: samakan dengan tema TSAQIB (Plus Jakarta Sans / Inter / Manrope) -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Inter:wght@400;500;600&family=Manrope:wght@600;700&display=swap" rel="stylesheet">

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

            /* Link "Kembali ke beranda" — target sentuh ≥40px, ada jarak dari logo */
            .auth-back{
                display:inline-flex; align-items:center; gap:.4rem;
                min-height:40px; padding:.5rem .9rem;
                margin-bottom:1.25rem;             /* tidak nimpa logo */
                color:#C0DD97; font-size:13px; font-weight:600;
                border:1px solid #1a3630; border-radius:9999px;
                background:rgba(15,38,33,.5); text-decoration:none;
                transition:background .2s ease, color .2s ease;
            }
            .auth-back:hover{ background:rgba(29,158,117,.18); color:#F7F5EF; }

            /* Logo — sedikit lebih kecil di layar sempit, max-width mencegah overflow */
            .auth-logo{ height:64px; width:auto; max-width:70vw; display:block; }
            @media (min-width:480px){ .auth-logo{ height:80px; } }

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
                <span aria-hidden="true">&larr;</span> Kembali ke beranda
            </a>

            <a href="/" aria-label="Beranda TSAQIB">
                <img src="{{ asset('images/icon/tsaqib-media.svg.png') }}" alt="TSAQIB" class="auth-logo" />
            </a>

            <div class="auth-card w-full sm:max-w-md mt-6 px-6 py-8 sm:px-8">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
