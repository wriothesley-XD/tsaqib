{{-- resources/views/profile/edit.blade.php --}}
@php($pageTitle = 'Profil Saya - TSAQIB SMAN 1 Bukittinggi')
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    @include('partials.theme-head')
    @php
        $userPosts = \App\Models\Post::where('user_id', Auth::id())->latest()->get();
    @endphp
</head>
<body class="text-[var(--cream)] font-sans antialiased min-h-screen flex flex-col">

    <!-- Unified TSAQIB Navbar -->
    @include('partials.navbar')

    <main class="flex-1 max-w-5xl mx-auto px-4 sm:px-6 py-10 sm:py-14 space-y-8 w-full">

        <!-- Title Banner -->
        <div class="flex items-center justify-between border-b border-white/10 pb-4">
            <div>
                <span class="text-xs font-bold text-[var(--gold)] uppercase tracking-wider block mb-1">Manajemen Pengguna</span>
                <h1 class="text-2xl font-display font-bold text-[var(--cream)]">Profil Saya</h1>
            </div>
            <div class="w-10 h-10 rounded-xl bg-[#01795F] text-white flex items-center justify-center font-bold text-lg shadow-sm">
                <i class="fa-solid fa-user"></i>
            </div>
        </div>

        <!-- 1. RINCIAN AKUN, ROLE SISTEM, & SELECTED COMMUNITY -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <!-- Informasional User (2 cols) -->
            <div class="md:col-span-2 tsaqib-card p-6 space-y-4">

                <!-- Avatar Header Row -->
                @php
                    $profilePics = glob(public_path('images/profile-pic/*.{jpg,jpeg,png,webp}'), GLOB_BRACE);
                    $randomAvatar = $profilePics
                        ? asset('images/profile-pic/' . basename($profilePics[array_rand($profilePics)]))
                        : asset('images/default-avatar.png');
                @endphp

                <div class="flex items-center space-x-4 pb-4 border-b border-white/10">
                    <img src="{{ $randomAvatar }}" alt="Avatar" class="w-16 h-16 rounded-full object-cover border border-white/15">
                    <div>
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-[#01795F]/15 text-[#3fd6b0] text-[10px] font-bold uppercase tracking-wider mb-1">
                            <i class="fa-solid fa-image text-[8px]"></i> Default Community Avatar
                        </span>
                        <h2 class="text-xl font-display font-extrabold text-[var(--cream)] leading-tight">{{ Auth::user()->name ?? 'Anggota TSAQIB' }}</h2>
                        <p class="text-xs text-white/50">{{ Auth::user()->email ?? '-' }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <span class="text-[10px] font-bold text-white/40 uppercase tracking-wider block">Nama Anggota</span>
                        <p class="text-base font-bold text-[var(--cream)]">{{ Auth::user()->name ?? 'Anggota TSAQIB' }}</p>
                    </div>

                    <div>
                        <span class="text-[10px] font-bold text-white/40 uppercase tracking-wider block">Email Terdaftar</span>
                        <p class="text-xs font-semibold text-white/70">{{ Auth::user()->email ?? '-' }}</p>
                    </div>
                </div>

                <div class="pt-3 border-t border-white/10 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <span class="text-[10px] font-bold text-white/40 uppercase tracking-wider block mb-1">Role Sistem</span>
                        @if(Auth::user()->role === 'admin')
                            <span class="inline-block px-2.5 py-1 rounded-md text-xs font-bold bg-amber-400/15 text-amber-300">
                                <i class="fa-solid fa-shield-halved mr-1"></i>Administrator Sistem
                            </span>
                        @else
                            <span class="inline-block px-2.5 py-1 rounded-md text-xs font-bold bg-white/10 text-white/70">
                                <i class="fa-solid fa-user-check mr-1"></i>Member TSAQIB
                            </span>
                        @endif
                    </div>

                    <div>
                        <span class="text-[10px] font-bold text-white/40 uppercase tracking-wider block mb-1">Minat Komunitas Utama</span>
                        <div class="flex items-center space-x-2">
                            <span class="inline-block px-2.5 py-1 rounded-md text-xs font-bold bg-[#01795F]/15 text-[#3fd6b0] uppercase">
                                <i class="fa-solid fa-layer-group mr-1"></i>{{ Auth::user()->selected_community ?? 'Belum Memilih' }}
                            </span>
                            <a href="{{ route('select-role') }}" class="text-[10px] text-[var(--gold)] font-bold hover:underline">
                                [Ganti Minat]
                            </a>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Card Logout Action (1 col) -->
            <div class="tsaqib-card p-6 flex flex-col justify-between">
                <div>
                    <h3 class="font-bold text-[var(--cream)] text-sm mb-1">Aksi Sesi</h3>
                    <p class="text-xs text-white/50">Keluar dari akun Anda untuk mengakhiri sesi aktif saat ini.</p>
                </div>

                <form method="POST" action="{{ route('logout') }}" class="mt-6">
                    @csrf
                    <button type="submit" class="w-full py-2.5 rounded-xl bg-red-500/10 hover:bg-red-500/20 border border-red-500/30 text-red-400 font-bold text-xs transition flex items-center justify-center space-x-2">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        <span>Keluar Akun (Logout)</span>
                    </button>
                </form>
            </div>

        </div>

        <!-- 2. DAFTAR POSTINGAN SAYA -->
        <div class="tsaqib-card p-6">
            <h2 class="text-base font-display font-bold text-[var(--cream)] mb-4 pb-3 border-b border-white/10 flex items-center space-x-2">
                <i class="fa-solid fa-newspaper text-[var(--gold)]"></i>
                <span>Postingan Saya ({{ count($userPosts ?? []) }})</span>
            </h2>

            @if(count($userPosts ?? []) > 0)
                <div class="space-y-3">
                    @foreach($userPosts as $post)
                        <div class="p-4 rounded-xl bg-white/5 border border-white/10 flex items-center justify-between gap-4">
                            <div>
                                <span class="text-[10px] font-bold text-[var(--gold)] uppercase tracking-wider block">{{ $post->community_slug }} • {{ $post->created_at->diffForHumans() }}</span>
                                <h4 class="font-bold text-sm text-[var(--cream)]">{{ $post->title }}</h4>
                                <p class="text-xs text-white/60 truncate max-w-lg mt-0.5">{{ $post->content }}</p>
                            </div>

                            <div class="flex items-center space-x-2 flex-shrink-0">
                                <form action="{{ route('posts.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus postingan ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-red-500/10 text-red-400 hover:bg-red-500/20">
                                        <i class="fa-solid fa-trash mr-1"></i>Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="py-8 text-center text-white/40 text-xs">
                    Anda belum pernah membuat postingan. Tekan tombol (+) di halaman Komunitas untuk menerbitkan postingan pertama Anda!
                </div>
            @endif
        </div>

    </main>

    <!-- Footer -->
    @include('partials.site-footer')

</body>
</html>
