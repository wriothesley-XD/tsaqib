<!-- resources/views/select-role.blade.php -->
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Karakter Komunitas - TSAQIB SMAN 1 Bukittinggi</title>
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
<body class="bg-slate-900 text-white font-sans antialiased min-h-screen flex flex-col justify-between p-4 sm:p-8">

    <div class="max-w-6xl mx-auto w-full my-auto text-center">

        <!-- Header Banner -->
        <div class="mb-8">
            <span class="inline-block px-3.5 py-1 rounded-full bg-[#01795F]/30 border border-[#01795F]/50 text-emerald-300 text-xs font-bold uppercase tracking-wider mb-2">
                Character Selection • Dunia TSAQIB
            </span>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight">
                Pilih Karakter Komunitas Anda
            </h1>
            <p class="text-slate-400 text-xs sm:text-sm mt-2 max-w-xl mx-auto">
                Pilih salah satu karakter minat komunitas di bawah. Pilihan ini akan disimpan ke akun Anda dan membuka feed komunitas utama.
            </p>
        </div>

        <!-- GAME CHARACTER SELECTION CARDS GRID -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 my-6 text-left">
            @foreach($daftarKomunitas as $index => $k)
                <form action="{{ route('select-role.store') }}" method="POST" class="h-full">
                    @csrf
                    <input type="hidden" name="community_slug" value="{{ $k['slug'] }}">
                    
                    <!-- GAME CHARACTER CARD -->
                    <div class="h-full bg-slate-800/90 border border-slate-700 hover:border-[#01795F] rounded-2xl p-5 shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 flex flex-col justify-between group">
                        
                        <div>
                            <!-- CHARACTER IMAGE PLACEHOLDER (RARED & EASY TO SWAP WITH FINAL ASSETS) -->
                            <div class="w-full h-44 rounded-xl bg-gradient-to-b from-slate-700 to-slate-800 border border-slate-700 flex flex-col items-center justify-center p-3 mb-4 relative overflow-hidden group-hover:border-[#01795F] transition">
                                @php
                                    $charImagePath = public_path('images/characters/' . $k['slug'] . '.png');
                                @endphp
                                @if(file_exists($charImagePath))
                                    <img src="{{ asset('images/characters/' . $k['slug'] . '.png') }}" alt="{{ $k['nama'] }}" class="w-full h-full object-contain">
                                @else
                                    <!-- PLACEHOLDER BADGE ICON -->
                                    <div class="w-16 h-16 rounded-full bg-[#01795F]/20 text-[#01795F] border border-[#01795F]/40 flex items-center justify-center font-bold text-2xl mb-2 group-hover:scale-110 transition">
                                        <i class="fa-solid fa-[#01795F] fa-shield-cat text-emerald-400"></i>
                                    </div>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">[Placeholder Karakter]</span>
                                @endif
                            </div>

                            <!-- COMMUNITY NAME & DESCRIPTION -->
                            <span class="text-[10px] font-bold text-emerald-400 uppercase tracking-wider block mb-1">
                                Komunitas #{{ $index + 1 }}
                            </span>
                            <h3 class="font-bold text-white text-base group-hover:text-emerald-400 transition mb-1">
                                {{ $k['nama'] }}
                            </h3>
                            <p class="text-xs text-slate-300 leading-relaxed line-clamp-3">
                                {{ $k['deskripsi_singkat'] }}
                            </p>
                        </div>

                        <!-- CHOOSE BUTTON -->
                        <div class="mt-5 pt-3 border-t border-slate-700/60">
                            <button type="submit"
                                    class="w-full py-2.5 rounded-xl bg-[#01795F] hover:bg-[#3F704D] text-white font-bold text-xs shadow-md transition flex items-center justify-center space-x-2">
                                <span>Pilih Komunitas</span>
                                <i class="fa-solid fa-arrow-right text-[11px]"></i>
                            </button>
                        </div>

                    </div>
                </form>
            @endforeach
        </div>

    </div>

    <!-- Footer -->
    <div class="text-center text-xs text-slate-500 py-4">
        &copy; {{ date('Y') }} TSAQIB • Forum Studi Islam SMAN 1 Bukittinggi
    </div>

</body>
</html>
