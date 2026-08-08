{{-- resources/views/open-recruitment/thank-you.blade.php --}}
@php($pageTitle = 'Terima Kasih - Pendaftaran FSI SMAN 1 Bukittinggi')
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    @include('partials.theme-head')
</head>
<body class="bg-[#10140F] text-[var(--cream)] font-sans antialiased min-h-screen flex flex-col">

    <!-- Unified Navbar -->
    @include('partials.navbar')

    <main class="flex-1 flex items-center justify-center p-4 py-16">
        <div class="max-w-xl w-full text-center tsaqib-card-flat rounded-3xl p-8 sm:p-12 shadow-2xl">

            <div class="w-20 h-20 rounded-full bg-[#01795F]/20 border border-[#01795F]/40 text-[#3fd6b0] flex items-center justify-center mx-auto mb-6 text-3xl shadow-inner animate-bounce">
                <i class="fa-solid fa-circle-check"></i>
            </div>

            <span class="eyebrow-pill eyebrow-pill-green mb-3">
                <i class="fa-solid fa-check text-[10px]"></i>
                Pendaftaran Berhasil Diterima
            </span>

            <h1 class="text-2xl sm:text-3xl font-display font-extrabold text-[var(--cream)] mb-3 mt-3">
                Jazakumullah Khairan!
            </h1>

            <p class="text-white/60 text-sm leading-relaxed mb-8">
                Formulir pendaftaran kamu telah berhasil disimpan di database TSAQIB dan notifikasi telah dikirimkan ke email pengurus FSI SMAN 1 Bukittinggi. Pengurus akan menghubungi kamu melalui Instagram/WhatsApp untuk agenda berikutnya.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                <a href="{{ route('landing') }}"
                   class="w-full sm:w-auto px-6 py-3 rounded-full bg-[var(--gold)] hover:brightness-110 text-[#10140F] font-label font-extrabold text-sm shadow-lg transition duration-200">
                    <i class="fa-solid fa-house-chimney mr-1.5"></i>Kembali ke Beranda
                </a>
                <a href="{{ route('komunitas.show', 'tahfidz') }}"
                   class="w-full sm:w-auto px-6 py-3 rounded-full bg-white/10 border border-white/15 hover:bg-white/20 text-[var(--cream)] text-sm font-bold transition duration-200">
                    Lihat Komunitas
                </a>
            </div>

        </div>
    </main>

    <!-- Footer -->
    @include('partials.site-footer')

</body>
</html>
