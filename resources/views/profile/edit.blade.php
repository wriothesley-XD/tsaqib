<!-- resources/views/profile/edit.blade.php -->
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - TSAQIB SMAN 1 Bukittinggi</title>
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
<body class="bg-slate-50 text-slate-900 font-sans antialiased min-h-screen flex flex-col">

    <!-- Unified TSAQIB Navbar -->
    @include('partials.navbar')

    <main class="flex-1 max-w-4xl mx-auto px-4 sm:px-6 py-10 sm:py-14 space-y-8 w-full">

        @if (session('status') === 'profile-updated')
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 text-sm flex items-center justify-between">
                <span>Profil berhasil diperbarui.</span>
                <i class="fa-solid fa-check text-green-500"></i>
            </div>
        @endif

        <!-- HEADER SECTION (Instagram-style) -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-8 shadow-sm flex flex-col md:flex-row items-center md:items-start gap-6 md:gap-10">
            <!-- Avatar Left -->
            <div class="flex-shrink-0">
                <x-community-avatar :user="$user" size="2xl" />
            </div>

            <!-- Profile Info Right -->
            <div class="flex-1 flex flex-col items-center md:items-start text-center md:text-left w-full space-y-4">
                
                <div class="flex flex-col md:flex-row md:items-center gap-3 md:gap-4 w-full justify-between md:justify-start">
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 leading-tight">{{ $user->name }}</h1>
                    <div class="flex items-center gap-2 justify-center md:justify-start">
                        <span class="inline-block px-3 py-1 rounded-full bg-[#01795F]/10 text-[#01795F] text-[10px] font-bold uppercase tracking-wider">
                            {{ $user->selected_community ?? 'Belum Memilih' }}
                        </span>
                        @if($user->role === 'admin')
                            <span class="inline-block px-3 py-1 rounded-full bg-amber-100 text-amber-900 text-[10px] font-bold uppercase tracking-wider">
                                Admin
                            </span>
                        @else
                            <span class="inline-block px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-[10px] font-bold uppercase tracking-wider">
                                Member
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Stats Row -->
                <div class="flex items-center gap-6 text-slate-700 text-sm">
                    <div><span class="font-bold text-slate-900">{{ count($userPosts) }}</span> Postingan</div>
                    <div>Bergabung <span class="font-bold text-slate-900">{{ $user->created_at->format('M Y') }}</span></div>
                </div>

                <!-- Bio -->
                <div class="text-sm text-slate-600 max-w-lg leading-relaxed whitespace-pre-wrap text-left w-full">
                    @if($user->bio)
                        {{ $user->bio }}
                    @else
                        <span class="italic text-slate-400">Belum ada bio. Tambahkan sedikit cerita tentang dirimu!</span>
                    @endif
                </div>

                <!-- Action Button -->
                <div class="pt-2 w-full md:w-auto">
                    <a href="#edit-profile" class="inline-block bg-slate-100 hover:bg-slate-200 text-slate-800 font-semibold py-2 px-6 rounded-lg text-sm transition duration-150 w-full md:w-auto text-center">
                        Edit Profil
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <!-- INFO CARD -->
            <div class="md:col-span-1 space-y-6">
                <!-- Info Section -->
                <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                    <h3 class="text-sm font-bold text-slate-900 mb-4 pb-2 border-b border-slate-100">Informasi Pribadi</h3>
                    <div class="space-y-4">
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Email Terdaftar</span>
                            <p class="text-sm font-semibold text-slate-700 break-words">{{ $user->email }}</p>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Minat Komunitas Utama</span>
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-semibold text-slate-700">{{ $user->selected_community ?? '-' }}</p>
                                <a href="{{ route('select-role') }}" class="text-[10px] text-[#01795F] font-bold hover:underline">
                                    [Ganti]
                                </a>
                            </div>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Anggota Sejak</span>
                            <p class="text-sm font-semibold text-slate-700">{{ $user->created_at->format('d F Y') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Account Actions Section -->
                <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                    <h3 class="text-sm font-bold text-slate-900 mb-4 pb-2 border-b border-slate-100">Aksi Akun</h3>
                    
                    <form method="POST" action="{{ route('logout') }}" class="mb-3">
                        @csrf
                        <button type="submit" class="w-full py-2.5 rounded-xl bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-700 font-bold text-xs transition flex items-center justify-center space-x-2">
                            <i class="fa-solid fa-right-from-bracket"></i>
                            <span>Keluar Akun (Logout)</span>
                        </button>
                    </form>

                    <form action="{{ route('profile.destroy') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun Anda? Semua data akan hilang.');">
                        @csrf
                        @method('DELETE')
                        <div class="mb-2">
                            <input type="password" name="password" placeholder="Password saat ini" required class="w-full text-xs rounded-lg border-slate-300 shadow-sm focus:border-red-500 focus:ring-red-500 py-2">
                            @error('password', 'userDeletion')
                                <span class="text-red-500 text-[10px] mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                        <button type="submit" class="w-full py-2.5 rounded-xl bg-red-50 hover:bg-red-100 border border-red-200 text-red-600 font-bold text-xs transition flex items-center justify-center space-x-2">
                            <i class="fa-solid fa-trash"></i>
                            <span>Hapus Akun Permanen</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- EDIT PROFILE FORM & POSTS -->
            <div class="md:col-span-2 space-y-6">
                
                <!-- Edit Profile Form -->
                <div id="edit-profile" class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm scroll-mt-24">
                    <h2 class="text-base font-bold text-slate-900 mb-4 flex items-center space-x-2">
                        <i class="fa-solid fa-pen-to-square text-[#01795F]"></i>
                        <span>Edit Profil</span>
                    </h2>

                    <form method="post" action="{{ route('profile.update') }}" class="space-y-4">
                        @csrf
                        @method('patch')

                        <div>
                            <label for="name" class="block text-xs font-bold text-slate-700 mb-1">Nama Lengkap</label>
                            <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required class="w-full rounded-lg border-slate-300 shadow-sm focus:border-[#01795F] focus:ring-[#01795F] text-sm">
                            @error('name') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-xs font-bold text-slate-700 mb-1">Email</label>
                            <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required class="w-full rounded-lg border-slate-300 shadow-sm focus:border-[#01795F] focus:ring-[#01795F] text-sm">
                            @error('email') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="bio" class="block text-xs font-bold text-slate-700 mb-1">Bio (Max 500 karakter)</label>
                            <textarea id="bio" name="bio" rows="4" maxlength="500" class="w-full rounded-lg border-slate-300 shadow-sm focus:border-[#01795F] focus:ring-[#01795F] text-sm">{{ old('bio', $user->bio) }}</textarea>
                            <div class="text-[10px] text-slate-500 text-right mt-1" id="bio-counter">0 / 500</div>
                            @error('bio') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="pt-2 flex justify-end">
                            <button type="submit" class="bg-[#01795F] hover:bg-[#01604b] text-white font-bold py-2 px-6 rounded-xl text-sm transition">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>

                <!-- My Posts Section -->
                <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                    <h2 class="text-base font-bold text-slate-900 mb-4 pb-3 border-b border-slate-100 flex items-center space-x-2">
                        <i class="fa-solid fa-newspaper text-[#01795F]"></i>
                        <span>Postingan Saya ({{ count($userPosts) }})</span>
                    </h2>

                    @if(count($userPosts) > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach($userPosts as $post)
                                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 flex flex-col justify-between">
                                    <div>
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="text-[10px] font-bold text-[#01795F] uppercase tracking-wider block">{{ $post->community_slug }}</span>
                                            <span class="text-[10px] text-slate-500">{{ $post->created_at->diffForHumans() }}</span>
                                        </div>
                                        <h4 class="font-bold text-sm text-slate-900 line-clamp-1 mb-1">{{ $post->title }}</h4>
                                        <p class="text-xs text-slate-600 line-clamp-2">{{ $post->content }}</p>
                                    </div>
                                    
                                    <div class="mt-4 pt-3 border-t border-slate-200 flex justify-end">
                                        <form action="{{ route('posts.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus postingan ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs font-semibold text-red-600 hover:text-red-800 flex items-center">
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

            </div>
        </div>

    </main>

    <!-- Footer -->
    <footer class="border-t border-slate-200 bg-white py-8 text-center text-xs text-slate-500 mt-auto">
        <div class="max-w-7xl mx-auto px-4">
            <p>&copy; {{ date('Y') }} TSAQIB • Forum Studi Islam SMAN 1 Bukittinggi. Hak Cipta Dilindungi.</p>
        </div>
    </footer>

    <script>
        // Simple character counter for bio
        const bioTextarea = document.getElementById('bio');
        const bioCounter = document.getElementById('bio-counter');
        
        if(bioTextarea && bioCounter) {
            const updateCounter = () => {
                const count = bioTextarea.value.length;
                bioCounter.textContent = `${count} / 500`;
                if(count >= 500) {
                    bioCounter.classList.add('text-red-500');
                } else {
                    bioCounter.classList.remove('text-red-500');
                }
            };
            
            bioTextarea.addEventListener('input', updateCounter);
            // Initial count
            updateCounter();
        }
    </script>
</body>
</html>
