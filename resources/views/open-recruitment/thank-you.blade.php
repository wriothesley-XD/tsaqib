{{-- resources/views/open-recruitment/thank-you.blade.php --}}
@php($pageTitle = 'Terima Kasih - Pendaftaran FSI SMAN 1 Bukittinggi')
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    @include('partials.theme-head')
</head>
<body class="text-[var(--cream)] font-sans antialiased min-h-screen flex flex-col">

    <!-- Unified Navbar -->
    @include('partials.navbar')

    <main class="flex-1 flex items-center justify-center p-4 py-16">
        <div class="max-w-xl w-full text-center tsaqib-card rounded-3xl p-8 sm:p-12 shadow-2xl relative overflow-hidden">
            <div class="absolute -top-12 -right-12 w-40 h-40 rounded-full bg-[var(--gold)]/10 blur-2xl pointer-events-none"></div>

            <div class="w-20 h-20 rounded-2xl bg-[#01795F]/20 border border-[#01795F]/40 text-[#3fd6b0] flex items-center justify-center mx-auto mb-6 text-3xl shadow-inner">
                <i class="fa-solid fa-circle-check"></i>
            </div>

            <span class="eyebrow-pill eyebrow-pill-green mb-3 inline-flex items-center gap-1.5">
                <i class="fa-solid fa-check text-[10px]"></i>
                Pendaftaran Berhasil Diterima
            </span>

            <h1 class="text-2xl sm:text-4xl font-display font-extrabold text-[var(--cream)] mb-3 mt-3">
                Jazakumullah <span class="text-[var(--gold)]">Khairan!</span>
            </h1>

            <p class="text-white/65 text-xs sm:text-sm leading-relaxed mb-8 max-w-md mx-auto">
                Formulir pendaftaran kamu telah berhasil disimpan di database TSAQIB dan diteruskan ke Pengurus FSI SMAN 1 Bukittinggi. Pengurus akan segera menghubungi kamu melalui Instagram/WhatsApp untuk agenda *welcoming* berikutnya.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                <a href="{{ route('landing') }}"
                   class="btn-gold text-xs px-6 py-3 w-full sm:w-auto shadow-lg">
                    <i class="fa-solid fa-house mr-1.5"></i>
                    <span>Kembali ke Beranda</span>
                </a>
                <a href="{{ route('komunitas', 'semua') }}"
                   class="btn-outline text-xs px-6 py-3 w-full sm:w-auto">
                    <span>Eksplorasi Komunitas FSI</span>
                </a>
            </div>

        </div>
    </main>

    <!-- Footer -->
    @include('partials.site-footer')

</body>
</html>
