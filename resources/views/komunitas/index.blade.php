<!-- resources/views/komunitas/index.blade.php -->
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feed Komunitas TSAQIB - SMAN 1 Bukittinggi</title>
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
<body class="bg-slate-50 text-slate-900 font-sans antialiased min-h-screen flex flex-col relative overflow-x-hidden">

    <!-- Unified TSAQIB Navbar -->
    @include('partials.navbar')

    <main class="flex-1 max-w-4xl mx-auto px-4 sm:px-6 py-8 sm:py-10 space-y-6 w-full">

        <!-- Title Banner -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
            <!-- Menghapus border-b dan padding bawah karena filter sudah tidak ada -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">
                        Timeline Feed Komunitas
                        <!-- Tambahan Dinamis: Menampilkan nama komunitas di judul jika bukan 'semua' -->
                        @if($currentSlug !== 'semua')
                            <span class="text-[#01795F]">- {{ collect($daftarKomunitas)->firstWhere('slug', $currentSlug)['nama'] ?? '' }}</span>
                        @endif
                    </h1>
                    <p class="text-slate-500 text-xs mt-0.5">Kumpulan postingan kegiatan, pengumuman, dan karya 13 komunitas FSI</p>
                </div>
                @auth
                    <button onclick="openCreateModal()" class="hidden sm:inline-flex items-center space-x-2 px-4 py-2.5 rounded-xl bg-[#01795F] hover:bg-[#3F704D] text-white font-semibold text-xs shadow-sm transition">
                        <i class="fa-solid fa-plus"></i>
                        <span>Buat Postingan</span>
                    </button>
                @endauth
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 rounded-xl bg-emerald-50 text-emerald-800 border border-emerald-200 text-xs font-semibold">
                {{ session('success') }}
            </div>
        @endif

        <!-- POSTS TIMELINE FEED -->
        <div class="space-y-4">
            @forelse($posts as $post)
                <article class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm hover:border-slate-300 transition duration-150">
                    
                    <!-- Post Header -->
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center space-x-3">
                            <div class="w-9 h-9 rounded-full bg-[#01795F]/10 text-[#01795F] flex items-center justify-center font-bold text-sm">
                                <i class="fa-solid fa-user"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-xs text-slate-900">{{ $post->user->name ?? 'Anggota TSAQIB' }}</h4>
                                <span class="text-[10px] text-slate-400">
                                    {{ $post->created_at->diffForHumans() }} • 
                                    <span class="font-bold text-[#01795F] uppercase">{{ $post->community_slug }}</span>
                                </span>
                            </div>
                        </div>

                        <!-- EDIT & DELETE BUTTONS FOR OWNER OR ADMIN -->
                        @if(Auth::check() && (Auth::id() === $post->user_id || Auth::user()->role === 'admin'))
                            <div class="flex items-center space-x-2">
                                <button onclick="toggleEditModal('{{ $post->id }}')" class="text-xs text-slate-500 hover:text-[#01795F] font-semibold px-2.5 py-1 rounded-lg bg-slate-100">
                                    <i class="fa-solid fa-pen mr-1"></i>Edit
                                </button>

                                <form action="{{ route('posts.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus postingan ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs text-red-500 hover:text-red-700 font-semibold px-2.5 py-1 rounded-lg bg-red-50">
                                        <i class="fa-solid fa-trash mr-1"></i>Hapus
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>

                    <!-- Post Body -->
                    <h3 class="font-bold text-base text-slate-900 mb-2 leading-snug">{{ $post->title }}</h3>
                    <p class="text-xs text-slate-700 leading-relaxed whitespace-pre-line mb-4">{{ $post->content }}</p>

                    <!-- ATTACHED IMAGE DISPLAY -->
                    @if($post->image_path)
                        <div class="rounded-xl overflow-hidden border border-slate-200 bg-slate-100 max-h-96 w-full flex items-center justify-center my-3">
                            <img src="{{ asset('storage/' . $post->image_path) }}" alt="{{ $post->title }}" class="w-full h-full object-cover max-h-96">
                        </div>
                    @endif

                    <!-- EDIT MODAL FORM -->
                    @if(Auth::check() && (Auth::id() === $post->user_id || Auth::user()->role === 'admin'))
                        <div id="edit-modal-{{ $post->id }}" class="hidden mt-4 pt-4 border-t border-slate-200">
                            <form action="{{ route('posts.update', $post->id) }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                                @csrf
                                @method('PUT')
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Judul Postingan</label>
                                    <input type="text" name="title" value="{{ $post->title }}" required class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-xs font-bold text-slate-900">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Isi Postingan</label>
                                    <textarea name="content" rows="3" required class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-xs text-slate-900">{{ $post->content }}</textarea>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Ganti Foto (Opsional)</label>
                                    <input type="file" name="image" accept="image/*" class="w-full text-xs text-slate-500">
                                </div>
                                <div class="flex justify-end space-x-2 pt-2">
                                    <button type="button" onclick="toggleEditModal('{{ $post->id }}')" class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-slate-100 text-slate-600">Batal</button>
                                    <button type="submit" class="px-4 py-1.5 rounded-lg text-xs font-semibold bg-[#01795F] text-white">Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>
                    @endif

                </article>
            @empty
                <div class="bg-white border border-slate-200 rounded-2xl p-8 text-center text-slate-400 text-xs">
                    Belum ada postingan di kategori ini. Tekan tombol (+) untuk menerbitkan postingan pertama!
                </div>
            @endforelse
        </div>

    </main>

    <!-- FLOATING ACTION BUTTON (+) -->
    @auth
        <div class="fixed bottom-6 right-6 z-40">
            <button onclick="openCreateModal()"
                    class="w-14 h-14 rounded-full bg-[#01795F] hover:bg-[#3F704D] text-white shadow-xl flex items-center justify-center text-2xl font-bold transition-all transform hover:scale-110 focus:outline-none"
                    title="Buat Postingan Baru">
                <i class="fa-solid fa-plus"></i>
            </button>
        </div>

        <!-- CREATE POST MODAL -->
        <div id="create-post-modal" class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-4 relative animate-in fade-in zoom-in duration-200">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="font-bold text-slate-900 text-base flex items-center space-x-2">
                        <i class="fa-solid fa-pen-to-square text-[#01795F]"></i>
                        <span>Buat Postingan Baru</span>
                    </h3>
                    <button onclick="closeCreateModal()" class="text-slate-400 hover:text-slate-600">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Judul Postingan</label>
                        <input type="text" name="title" required placeholder="Contoh: Dokumen Kegiatan Tahfidz..."
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900 focus:outline-none focus:border-[#01795F]">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Kategori Komunitas</label>
                        <select name="community_slug" required
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900 focus:outline-none focus:border-[#01795F]">
                            @foreach($daftarKomunitas as $k)
                                <option value="{{ $k['slug'] }}" {{ (Auth::user()->selected_community == $k['slug'] || $currentSlug == $k['slug']) ? 'selected' : '' }}>
                                    {{ $k['nama'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Isi Postingan / Deskripsi</label>
                        <textarea name="content" rows="4" required placeholder="Tuliskan materi, pengumuman, atau rincian kegiatan..."
                                  class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900 focus:outline-none focus:border-[#01795F]"></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Lampirkan Foto (Opsional)</label>
                        <input type="file" name="image" accept="image/*"
                               class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-[#01795F]/10 file:text-[#01795F]">
                    </div>

                    <div class="pt-2 flex items-center justify-end space-x-2 border-t border-slate-100">
                        <button type="button" onclick="closeCreateModal()" class="px-4 py-2 rounded-xl text-xs font-semibold bg-slate-100 text-slate-600">Batal</button>
                        <button type="submit" class="px-5 py-2 rounded-xl text-xs font-semibold bg-[#01795F] hover:bg-[#3F704D] text-white shadow-sm">
                            <i class="fa-solid fa-paper-plane mr-1.5"></i>Terbitkan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endauth

    <!-- Footer -->
    <footer class="border-t border-slate-200 bg-white py-8 text-center text-xs text-slate-500 mt-auto">
        <div class="max-w-7xl mx-auto px-4">
            <p>&copy; {{ date('Y') }} TSAQIB • Forum Studi Islam SMAN 1 Bukittinggi. Hak Cipta Dilindungi.</p>
        </div>
    </footer>

    <!-- SCRIPT UNTUK MODAL SAJA (Filter JS sudah dihapus) -->
    <script>
        // Logika Modal Create & Edit Post
        function openCreateModal() {
            document.getElementById('create-post-modal').classList.remove('hidden');
        }
        function closeCreateModal() {
            document.getElementById('create-post-modal').classList.add('hidden');
        }
        function toggleEditModal(id) {
            const el = document.getElementById('edit-modal-' + id);
            if (el) {
                el.classList.toggle('hidden');
            }
        }
    </script>

</body>
</html>