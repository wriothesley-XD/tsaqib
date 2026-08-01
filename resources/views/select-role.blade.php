<!-- resources/views/select-role.blade.php -->
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Minat Komunitas Anda - TSAQIB SMAN 1 Bukittinggi</title>
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
<body class="bg-slate-50 text-slate-900 font-sans antialiased min-h-screen flex flex-col justify-between p-4 sm:p-8">

    <div class="max-w-4xl mx-auto w-full my-auto text-center">

        <!-- Top Header -->
        <span class="inline-block px-3 py-1 rounded-full bg-[#01795F]/10 text-[#01795F] text-xs font-semibold uppercase tracking-wider mb-3">
            Langkah Utama • Pembaharuan Minat
        </span>
        <h1 class="text-2xl sm:text-4xl font-bold text-slate-900 tracking-tight">
            Pilih Minat Komunitas Utama Anda
        </h1>
        <p class="text-slate-600 text-xs sm:text-sm mt-2 max-w-lg mx-auto">
            Pilihan minat komunitas ini akan disimpan ke akun Anda dan langsung membuka feed komunitas utama TSAQIB SMAN 1 Bukittinggi.
        </p>

        <!-- CAROUSEL SLIDER SELECTION WITH POST FORM -->
        <div class="my-8 relative">
            <div class="flex items-center space-x-4 overflow-x-auto pb-4 pt-2 px-2 scrollbar-thin scrollbar-thumb-slate-300 snap-x">
                @foreach($daftarKomunitas as $index => $k)
                    <form action="{{ route('select-role.store') }}" method="POST" class="snap-center flex-shrink-0">
                        @csrf
                        <input type="hidden" name="community_slug" value="{{ $k['slug'] }}">
                        <button type="submit"
                                class="w-64 sm:w-72 bg-white border border-slate-200 hover:border-[#01795F] rounded-2xl p-6 text-left shadow-sm hover:shadow-md transition duration-200 group cursor-pointer">
                            <div class="w-12 h-12 rounded-xl bg-[#01795F]/10 text-[#01795F] flex items-center justify-center font-bold text-xl mb-4 group-hover:bg-[#01795F] group-hover:text-white transition duration-200">
                                <i class="fa-solid fa-layer-group"></i>
                            </div>
                            <h3 class="font-bold text-slate-900 text-base group-hover:text-[#01795F] transition">{{ $k['nama'] }}</h3>
                            <p class="text-xs text-slate-500 mt-1 line-clamp-2">{{ $k['deskripsi_singkat'] }}</p>
                            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs font-semibold text-[#01795F]">
                                <span>Pilih Komunitas Ini &rarr;</span>
                                <i class="fa-solid fa-check-circle text-sm"></i>
                            </div>
                        </button>
                    </form>
                @endforeach
            </div>
        </div>

    </div>

    <div class="text-center text-xs text-slate-400 py-4">
        &copy; {{ date('Y') }} TSAQIB • Forum Studi Islam SMAN 1 Bukittinggi
    </div>

</body>
</html>
