{{--
    resources/views/partials/theme-head.blade.php
    ==================================================
    SATU SUMBER KEBENARAN untuk tema gelap TSAQIB (sama kayak landing.blade.php).
    Include partial ini di dalam <head> tiap halaman publik — jadi semua halaman
    otomatis konsisten warna, font, dan komponennya. Ganti tema? Cukup edit file ini.

    Halaman bisa set judul lewat @php($pageTitle = '...') SEBELUM @include ini.
    Halaman bisa tambah CSS sendiri lewat @push('styles') ... @endpush.

    PENTING — penempatan @php query DB:
    Taruh block @php ... @endphp (yang query DB/model) DI DALAM <head>, SETELAH
    @include('partials.theme-head') ini — BUKAN di atas sebelum <!DOCTYPE>.
    Kalau ditaruh paling atas (setelah inline @php($pageTitle)), Blade salah-compile
    dan query-nya jadi rusak. Lihat perpustakaan.blade.php untuk contoh yang benar.
--}}
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $pageTitle ?? 'TSAQIB — Forum Studi Islam SMAN 1 Bukittinggi' }}</title>
@vite('resources/css/app.css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Inter:wght@400;500;600&family=Manrope:wght@600;700&display=swap" rel="stylesheet">

<style>
    :root{
        --cream:#F7F5EF;
        --ink:#10140F;
        --green:#01795F;
        --green-dark:#3F704D;
        --gold:#C9A66B;
    }

    /* Brand mark (kartu logo "TS" gradient) */
    .brand-mark{
        width:42px;height:42px;border-radius:12px;
        background:linear-gradient(135deg, var(--green), var(--green-dark));
        display:flex;align-items:center;justify-content:center;
        font-family:'Plus Jakarta Sans',sans-serif;font-weight:800;color:var(--cream);
        box-shadow:0 4px 14px rgba(1,121,95,.4);
    }
    .brand-mark-sm{ width:40px;height:40px;border-radius:11px;font-size:18px; }

    /* Eyebrow pill (label kecil di atas judul) */
    .eyebrow-pill{
        display:inline-flex;align-items:center;gap:6px;
        padding:5px 14px;border-radius:999px;
        background:rgba(247,245,239,.08);
        border:1px solid rgba(247,245,239,.18);
        color:var(--cream);
        font-family:'Manrope',sans-serif;font-weight:700;
        font-size:11px;letter-spacing:.06em;text-transform:uppercase;
        backdrop-filter:blur(4px);
    }
    .eyebrow-pill-green{
        background:rgba(1,121,95,.14);
        border-color:rgba(1,121,95,.3);
        color:#5fd3b0;
    }

    /* CTA utama (tombol hijau) */
    .cta-primary{
        background:var(--green);
        transition:background .2s ease, transform .2s ease, box-shadow .2s ease;
        box-shadow:0 10px 30px -8px rgba(1,121,95,.6);
    }
    .cta-primary:hover{ background:var(--green-dark); transform:translateY(-2px); }

    /* ===== Kartu kaca gelap — pengganti "bg-white border-slate-200 rounded-2xl" ===== */
    .tsaqib-card{
        background:rgba(247,245,239,.04);
        border:1px solid rgba(247,245,239,.10);
        border-radius:1rem;
        transition:background .2s ease, border-color .2s ease, transform .2s ease, box-shadow .2s ease;
    }
    .tsaqib-card:hover{
        background:rgba(247,245,239,.065);
        border-color:rgba(247,245,239,.18);
    }
    .tsaqib-card-flat{ /* varian tanpa efek hover */
        background:rgba(247,245,239,.04);
        border:1px solid rgba(247,245,239,.10);
        border-radius:1rem;
    }

    /* ===== Form control gelap — pengganti "bg-slate-50 border-slate-200" ===== */
    .tsaqib-input{
        background:rgba(247,245,239,.04);
        border:1px solid rgba(247,245,239,.14);
        color:var(--cream);
        border-radius:0.75rem;
        transition:border-color .2s ease, box-shadow .2s ease, background .2s ease;
    }
    .tsaqib-input::placeholder{ color:rgba(247,245,239,.4); }
    .tsaqib-input:focus{
        outline:none;
        background:rgba(247,245,239,.06);
        border-color:var(--green);
        box-shadow:0 0 0 1px rgba(1,121,95,.45);
    }
    select.tsaqib-input option{ background:#161a14; color:var(--cream); }

    /* Ikon bulat berlabel (untuk kartu info, grid komunitas, dsb.) */
    .icon-chip{
        display:flex;align-items:center;justify-content:center;
        border-radius:0.75rem;
        background:rgba(1,121,95,.14);
        color:#34c9a0;
    }

    @media (prefers-reduced-motion: reduce){
        *{ transition-duration:.01ms !important; animation-duration:.01ms !important; }
    }

    @stack('styles')
</style>
