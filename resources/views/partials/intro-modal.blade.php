{{--
    resources/views/partials/intro-modal.blade.php
    ===============================================
    Modal onboarding "Panduan TSAQIB" — carousel 4 slide.

    • Muncul OTOMATIS SEKALI per browser (localStorage "sudah_lihat_intro").
    • Bisa dibuka ulang kapan saja: elemen apa pun ber-atribut [data-open-intro]
      (lihat item "Panduan TSAQIB" di dropdown Lainnya, partials/navbar).
    • Self-contained (markup + <style> + <script> satu file) supaya bisa
      di-include dari layouts/master ATAU landing.blade.php — keduanya
      menyediakan var tema --gold/--cream (theme-head / :root landing).
    • Animasi fade antar slide; prefers-reduced-motion dihormati.
--}}
<div id="intro-modal" role="dialog" aria-modal="true" aria-labelledby="intro-title">
    <div class="intro-backdrop" data-intro-close></div>

    <div id="intro-panel" tabindex="-1"
         class="relative w-full max-w-md rounded-2xl border border-white/10 bg-[#161a14] shadow-[0_24px_60px_-15px_rgba(0,0,0,0.8)] overflow-hidden">

        {{-- ===== SLIDES ===== --}}
        <div class="px-7 pt-8 pb-5 text-center">
            <section class="intro-slide is-active">
                <img src="{{ asset('images/icon/tsaqib-media.svg.png') }}" alt=""
                     class="h-16 w-auto mx-auto mb-4 object-contain">
                <h2 id="intro-title" class="font-display text-xl font-extrabold text-[var(--cream)]">Selamat datang di TSAQIB</h2>
                <p class="mt-2.5 text-sm leading-relaxed text-white/65">
                    Rumah digital FSI SMAN 1 Bukittinggi. Belajar, membaca, dan
                    berkarya — semuanya dalam satu tempat. Kenali dulu tiga zona utamanya.
                </p>
            </section>

            <section class="intro-slide">
                <span class="intro-ikon"><i class="fa-solid fa-flask" aria-hidden="true"></i></span>
                <h2 class="font-display text-xl font-extrabold text-[var(--cream)]">Laboratorium PAI</h2>
                <p class="mt-2.5 text-sm leading-relaxed text-white/65">
                    Zona praktikum PAI: telusuri modul pembelajaran, kerjakan tugas
                    siswa, dan kenali profil guru laboratorium.
                </p>
            </section>

            <section class="intro-slide">
                <span class="intro-ikon"><i class="fa-solid fa-book-open" aria-hidden="true"></i></span>
                <h2 class="font-display text-xl font-extrabold text-[var(--cream)]">Perpustakaan Digital</h2>
                <p class="mt-2.5 text-sm leading-relaxed text-white/65">
                    Baca langsung atau unduh buletin &amp; e-book. Aksesnya publik —
                    tanpa perlu login.
                </p>
            </section>

            <section class="intro-slide">
                <span class="intro-ikon"><i class="fa-solid fa-users" aria-hidden="true"></i></span>
                <h2 class="font-display text-xl font-extrabold text-[var(--cream)]">Komunitas</h2>
                <p class="mt-2.5 text-sm leading-relaxed text-white/65">
                    Ada 7 circle minat &amp; bakat untuk berkarya bersama. Pilih
                    circle-mu, kirim postingan, dan mulai berdiskusi lewat menu Komunitas.
                </p>
            </section>
        </div>

        {{-- ===== FOOTER: Lewati | dots | Lanjut ===== --}}
        <div class="flex items-center justify-between gap-3 px-6 py-4 border-t border-white/10 bg-black/20">
            <button type="button" data-intro-close
                    class="text-xs font-semibold text-white/50 hover:text-white transition-colors duration-200 px-2 py-2 rounded-lg cursor-pointer">
                Lewati
            </button>

            <div id="intro-dots" class="flex items-center gap-2" role="tablist" aria-label="Slide panduan">
                <button type="button" class="intro-dot is-on" aria-label="Slide 1" aria-current="true"></button>
                <button type="button" class="intro-dot" aria-label="Slide 2"></button>
                <button type="button" class="intro-dot" aria-label="Slide 3"></button>
                <button type="button" class="intro-dot" aria-label="Slide 4"></button>
            </div>

            <button type="button" id="intro-next"
                    class="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-full text-xs font-bold bg-[var(--gold)] text-[#0D2818] hover:brightness-110 transition duration-200 cursor-pointer">
                Lanjut <span aria-hidden="true">→</span>
            </button>
        </div>
    </div>
</div>

<style>
    #intro-modal{ display:none; position:fixed; inset:0; z-index:90; align-items:center; justify-content:center; padding:1rem; }
    #intro-modal.is-open{ display:flex; }
    .intro-backdrop{ position:absolute; inset:0; background:rgba(0,0,0,.7); backdrop-filter:blur(4px); }

    .intro-slide{ display:none; }
    .intro-slide.is-active{ display:block; animation:introFade .35s ease-out; }
    @keyframes introFade{ from{ opacity:0; transform:translateY(6px); } to{ opacity:1; transform:none; } }

    .intro-ikon{
        display:flex; align-items:center; justify-content:center;
        width:56px; height:56px; margin:0 auto 1rem; border-radius:1rem;
        background:rgba(201,166,107,.14); color:var(--gold); font-size:1.35rem;
    }
    .intro-dot{
        width:8px; height:8px; padding:0; border-radius:9999px; cursor:pointer;
        border:0; background:rgba(247,245,239,.25);
        transition:background .2s ease, width .2s ease;
    }
    .intro-dot.is-on{ background:var(--gold); width:20px; }

    #intro-panel:focus{ outline:none; }
    @media (prefers-reduced-motion: reduce){
        .intro-slide.is-active{ animation:none; }
    }
</style>

<script>
(function () {
    var KEY = 'sudah_lihat_intro';
    var modal = document.getElementById('intro-modal');
    if (!modal) return;

    var slides = modal.querySelectorAll('.intro-slide');
    var dots   = modal.querySelectorAll('.intro-dot');
    var next   = document.getElementById('intro-next');
    var panel  = document.getElementById('intro-panel');
    var i = 0, hadLock = false;

    function show(n) {
        i = n;
        slides.forEach(function (s, k) { s.classList.toggle('is-active', k === n); });
        dots.forEach(function (d, k) {
            d.classList.toggle('is-on', k === n);
            if (k === n) d.setAttribute('aria-current', 'true'); else d.removeAttribute('aria-current');
        });
        next.innerHTML = (n === slides.length - 1)
            ? 'Mulai Jelajahi'
            : 'Lanjut <span aria-hidden="true">→</span>';
    }

    function open() {
        hadLock = document.body.classList.contains('overflow-hidden');
        modal.classList.add('is-open');
        document.body.classList.add('overflow-hidden');
        show(0);
        panel.focus();
    }

    function close() {
        try { localStorage.setItem(KEY, '1'); } catch (e) {}
        modal.classList.remove('is-open');
        if (!hadLock) document.body.classList.remove('overflow-hidden');
    }

    next.addEventListener('click', function () { i === slides.length - 1 ? close() : show(i + 1); });
    dots.forEach(function (d, k) { d.addEventListener('click', function () { show(k); }); });
    modal.querySelectorAll('[data-intro-close]').forEach(function (el) {
        el.addEventListener('click', close);
    });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && modal.classList.contains('is-open')) close();
    });

    // Reopen via [data-open-intro] (navbar "Panduan TSAQIB"). preventDefault karena
    // href-nya "#"; dropdown Lainnya ikut ditutup agar tak tertinggal terbuka.
    document.addEventListener('click', function (e) {
        var t = e.target.closest('[data-open-intro]');
        if (!t) return;
        e.preventDefault();
        var lm = document.getElementById('lainnya-menu');
        var lb = document.getElementById('lainnya-toggle');
        if (lm) lm.classList.remove('is-open');
        if (lb) lb.setAttribute('aria-expanded', 'false');
        open();
    });

    // Auto-sekali untuk pengunjung baru. try/catch: localStorage bisa diblokir
    // (private mode) — gagalnya jangan sampai mematikan seluruh halaman.
    try { if (!localStorage.getItem(KEY)) open(); } catch (e) {}
})();
</script>
