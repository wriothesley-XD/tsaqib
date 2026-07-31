<!-- resources/views/open-recruitment/thank-you.blade.php -->
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terima Kasih - Pendaftaran FSI SMAN 1 Bukittinggi</title>
    @vite('resources/css/app.css')
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                }
            }
        }
    </script>
</head>
<body class="bg-slate-950 text-slate-100 font-sans antialiased min-h-screen flex flex-col">

    <!-- Unified Navbar -->
    @include('partials.navbar')

    <main class="flex-1 flex items-center justify-center p-4 py-16">
        <div class="max-w-xl w-full text-center bg-slate-900/90 border border-slate-800 rounded-3xl p-8 sm:p-12 shadow-2xl backdrop-blur-xl">
            
            <div class="w-20 h-20 rounded-full bg-emerald-500/20 border border-emerald-400/40 text-emerald-400 flex items-center justify-center mx-auto mb-6 text-3xl shadow-inner animate-bounce">
                <i class="fa-solid fa-circle-check"></i>
            </div>

            <span class="inline-block px-3 py-1 rounded-full text-xs font-bold uppercase tracking-widest bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 mb-3">
                Pendaftaran Berhasil Diterima
            </span>

            <h1 class="text-2xl sm:text-3xl font-extrabold text-white mb-3">
                Jazakumullah Khairan!
            </h1>

            <p class="text-slate-300 text-sm leading-relaxed mb-8">
                Formulir pendaftaran kamu telah berhasil disimpan di database TSAQIB dan notifikasi telah dikirimkan ke email pengurus FSI SMAN 1 Bukittinggi. Pengurus akan menghubungi kamu melalui Instagram/WhatsApp untuk agenda berikutnya.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                <a href="{{ route('landing') }}"
                   class="w-full sm:w-auto px-6 py-3 rounded-2xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-extrabold text-sm shadow-lg transition duration-200">
                    <i class="fa-solid fa-house-chimney mr-1.5"></i>Kembali ke Pulau TSAQIB
                </a>
                <a href="{{ route('komunitas.show', 'tahfidz') }}"
                   class="w-full sm:w-auto px-6 py-3 rounded-2xl bg-slate-800 border border-slate-700 hover:bg-slate-700 text-slate-200 text-sm font-bold transition duration-200">
                    Lihat Komunitas
                </a>
            </div>

        </div>
    </main>

    <!-- Footer -->
    <footer class="border-t border-slate-900 bg-slate-950 py-8 text-center text-xs text-slate-500">
        <div class="max-w-7xl mx-auto px-4">
            <p>&copy; {{ date('Y') }} TSAQIB - Forum Studi Islam SMAN 1 Bukittinggi. Hak Cipta Dilindungi.</p>
        </div>
    </footer>

</body>
</html>
