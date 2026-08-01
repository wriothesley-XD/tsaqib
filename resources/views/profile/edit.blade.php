<!-- resources/views/profile/edit.blade.php -->
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Akun Saya - TSAQIB SMAN 1 Bukittinggi</title>
    @vite('resources/css/app.css')
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    @php
        $userPosts = \App\Models\Post::where('user_id', Auth::id())->latest()->get();
    @endphp

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
<body class="bg-slate-50 text-slate-900 font-sans antialiased min-h-screen flex flex-col">

    <!-- Unified TSAQIB Navbar (6 Items) -->
    @include('partials.navbar')

    <main class="flex-1 max-w-5xl mx-auto px-4 sm:px-6 py-10 sm:py-14 space-y-8 w-full">

        <!-- Title Banner -->
        <div class="flex items-center justify-between border-b border-slate-200 pb-4">
            <div>
                <span class="text-xs font-bold text-[#01795F] uppercase tracking-wider block mb-1">Manajemen Pengguna</span>
                <h1 class="text-2xl font-bold text-slate-900">Profil Saya</h1>
            </div>
            <div class="w-10 h-10 rounded-xl bg-[#01795F] text-white flex items-center justify-center font-bold text-lg shadow-sm">
                <i class="fa-solid fa-user"></i>
            </div>
        </div>

        <!-- 1. RINCIAN AKUN, ROLE SISTEM, & SELECTED COMMUNITY -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <!-- Informasional User (2 cols) -->
            <div class="md:col-span-2 bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-4">
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Nama Anggota</span>
                        <p class="text-base font-bold text-slate-900">{{ Auth::user()->name ?? 'Anggota TSAQIB' }}</p>
                    </div>

                    <div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Email Terdaftar</span>
                        <p class="text-xs font-semibold text-slate-700">{{ Auth::user()->email ?? '-' }}</p>
                    </div>
                </div>

                <div class="pt-2 border-t border-slate-100 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Role Sistem</span>
                        @if(Auth::user()->role === 'admin')
                            <span class="inline-block px-2.5 py-1 rounded-md text-xs font-bold bg-amber-100 text-amber-900">
                                <i class="fa-solid fa-shield-halved mr-1"></i>Administrator Sistem
                            </span>
                        @else
                            <span class="inline-block px-2.5 py-1 rounded-md text-xs font-bold bg-slate-100 text-slate-800">
                                <i class="fa-solid fa-user-check mr-1"></i>Member TSAQIB
                            </span>
                        @endif
                    </div>

                    <div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Minat Komunitas Utama</span>
                        <div class="flex items-center space-x-2">
                            <span class="inline-block px-2.5 py-1 rounded-md text-xs font-bold bg-[#01795F]/10 text-[#01795F] uppercase">
                                <i class="fa-solid fa-layer-group mr-1"></i>{{ Auth::user()->selected_community ?? 'Belum Memilih' }}
                            </span>
                            <a href="{{ route('select-role') }}" class="text-[10px] text-[#01795F] font-bold hover:underline">
                                [Ganti]
                            </a>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Card Logout Action (1 col) -->
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm flex flex-col justify-between">
                <div>
                    <h3 class="font-bold text-slate-900 text-sm mb-1">Aksi Sesi</h3>
                    <p class="text-xs text-slate-500">Keluar dari akun Anda untuk mengakhiri sesi aktif saat ini.</p>
                </div>

                <form method="POST" action="{{ route('logout') }}" class="mt-6">
                    @csrf
                    <button type="submit" class="w-full py-2.5 rounded-xl bg-red-50 hover:bg-red-100 border border-red-200 text-red-600 font-bold text-xs transition flex items-center justify-center space-x-2">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        <span>Keluar Akun (Logout)</span>
                    </button>
                </form>
            </div>

        </div>

        <!-- 2. DAFTAR POSTINGAN SAYA -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
            <h2 class="text-base font-bold text-slate-900 mb-4 pb-3 border-b border-slate-100 flex items-center space-x-2">
                <i class="fa-solid fa-newspaper text-[#01795F]"></i>
                <span>Postingan Saya ({{ count($userPosts) }})</span>
            </h2>

            @if(count($userPosts) > 0)
                <div class="space-y-3">
                    @foreach($userPosts as $post)
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-between gap-4">
                            <div>
                                <span class="text-[10px] font-bold text-[#01795F] uppercase tracking-wider block">{{ $post->community_slug }} • {{ $post->created_at->diffForHumans() }}</span>
                                <h4 class="font-bold text-sm text-slate-900">{{ $post->title }}</h4>
                                <p class="text-xs text-slate-600 truncate max-w-lg mt-0.5">{{ $post->content }}</p>
                            </div>

                            <div class="flex items-center space-x-2 flex-shrink-0">
                                <form action="{{ route('posts.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus postingan ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-red-50 text-red-600 hover:bg-red-100">
                                        <i class="fa-solid fa-trash mr-1"></i>Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="py-8 text-center text-slate-400 text-xs">
                    Anda belum pernah membuat postingan. Tekan tombol (+) di halaman Komunitas untuk menerbitkan postingan pertama Anda!
                </div>
            @endif
        </div>

    </main>

    <!-- Footer -->
    <footer class="border-t border-slate-200 bg-white py-8 text-center text-xs text-slate-500">
        <div class="max-w-7xl mx-auto px-4">
            <p>&copy; {{ date('Y') }} TSAQIB • Forum Studi Islam SMAN 1 Bukittinggi. Hak Cipta Dilindungi.</p>
        </div>
    </footer>

</body>
</html>
