{{-- resources/views/komunitas/index.blade.php --}}
@php($pageTitle = 'Feed Komunitas TSAQIB - SMAN 1 Bukittinggi')
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    @include('partials.theme-head')
</head>
<body class="bg-[#10140F] text-[var(--cream)] font-sans antialiased min-h-screen flex flex-col relative overflow-x-hidden">

    <!-- Unified TSAQIB Navbar -->
    @include('partials.navbar')

    <main class="flex-1 max-w-4xl mx-auto px-4 sm:px-6 py-8 sm:py-10 space-y-6 w-full">

        <!-- Title Banner -->
        <div class="tsaqib-card p-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-display font-bold text-[var(--cream)]">
                        Timeline Feed Komunitas
                        @if($currentSlug !== 'semua')
                            <span class="text-[var(--gold)]">- {{ collect($daftarKomunitas)->firstWhere('slug', $currentSlug)['nama'] ?? '' }}</span>
                        @endif
                    </h1>
                    <p class="text-white/50 text-xs mt-0.5">Kumpulan postingan kegiatan, pengumuman, dan karya 13 komunitas FSI</p>
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
            <div class="p-4 rounded-xl bg-[#01795F]/15 text-[#3fd6b0] border border-[#01795F]/30 text-xs font-semibold">
                {{ session('success') }}
            </div>
        @endif

        <!-- POSTS TIMELINE FEED -->
        <div class="space-y-4">
            @forelse($posts as $post)
                <article class="tsaqib-card p-6 transition duration-150">

                    <!-- Post Header -->
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center space-x-3">
                            <x-community-avatar :user="$post->user" :slug="$post->community_slug" size="md" />
                            <div>
                                <h4 class="font-bold text-xs text-[var(--cream)]">{{ $post->user->name ?? 'Anggota TSAQIB' }}</h4>
                                <span class="text-[10px] text-white/40">
                                    {{ $post->created_at->diffForHumans() }} •
                                    <span class="font-bold text-[var(--gold)] uppercase">{{ $post->community_slug }}</span>
                                </span>
                            </div>
                        </div>

                        <!-- EDIT & DELETE BUTTONS FOR OWNER OR ADMIN -->
                        @if(Auth::check() && (Auth::id() === $post->user_id || Auth::user()->role === 'admin'))
                            <div class="flex items-center space-x-2">
                                <button onclick="toggleEditModal('{{ $post->id }}')" class="text-xs text-white/60 hover:text-white font-semibold px-2.5 py-1 rounded-lg bg-white/10">
                                    <i class="fa-solid fa-pen mr-1"></i>Edit
                                </button>

                                <form action="{{ route('posts.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus postingan ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs text-red-400 hover:text-red-300 font-semibold px-2.5 py-1 rounded-lg bg-red-500/10">
                                        <i class="fa-solid fa-trash mr-1"></i>Hapus
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>

                    <!-- Post Body -->
                    <h3 class="font-bold text-base text-[var(--cream)] mb-2 leading-snug">{{ $post->title }}</h3>
                    <p class="text-xs text-white/75 leading-relaxed whitespace-pre-line mb-4">{{ $post->content }}</p>

                    <!-- ATTACHED IMAGE DISPLAY -->
                    @if($post->image_path)
                        <div class="rounded-xl overflow-hidden border border-white/10 bg-white/5 max-h-96 w-full flex items-center justify-center my-3">
                            <img src="{{ asset('storage/' . $post->image_path) }}" alt="{{ $post->title }}" class="w-full h-full object-contain max-h-96">
                        </div>
                    @endif

                    <!-- EDIT MODAL FORM -->
                    @if(Auth::check() && (Auth::id() === $post->user_id || Auth::user()->role === 'admin'))
                        <div id="edit-modal-{{ $post->id }}" class="hidden mt-4 pt-4 border-t border-white/10">
                            <form action="{{ route('posts.update', $post->id) }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                                @csrf
                                @method('PUT')
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-white/50 mb-1">Judul Postingan</label>
                                    <input type="text" name="title" value="{{ $post->title }}" required class="tsaqib-input w-full px-3 py-2 text-xs font-bold">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-white/50 mb-1">Isi Postingan</label>
                                    <textarea name="content" rows="3" required class="tsaqib-input w-full px-3 py-2 text-xs">{{ $post->content }}</textarea>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-white/50 mb-1">Ganti Foto (Opsional)</label>
                                    <input type="file" name="image" accept="image/*" class="w-full text-xs text-white/50">
                                </div>
                                <div class="flex justify-end space-x-2 pt-2">
                                    <button type="button" onclick="toggleEditModal('{{ $post->id }}')" class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-white/10 text-white/70">Batal</button>
                                    <button type="submit" class="px-4 py-1.5 rounded-lg text-xs font-semibold bg-[#01795F] text-white">Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>
                    @endif

                </article>
            @empty
                <div class="tsaqib-card-flat p-8 text-center text-white/40 text-xs">
                    Belum ada postingan di kategori ini. Tekan tombol (+) untuk menerbitkan postingan pertama!
                </div>
            @endforelse
        </div>

        {{-- Pagination controls (themed) — slug komunitas ada di path, jadi
             pindah halaman tidak mengubah kategori. ?page=N shareable. --}}
        @if ($posts->hasPages())
            <div class="pt-2">
                {{ $posts->links('partials.pagination') }}
            </div>
        @endif

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
        <div id="create-post-modal" class="hidden fixed inset-0 z-50 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
            <div class="bg-[#161a14] border border-white/10 rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-4 relative">
                <div class="flex items-center justify-between pb-3 border-b border-white/10">
                    <h3 class="font-display font-bold text-[var(--cream)] text-base flex items-center space-x-2">
                        <i class="fa-solid fa-pen-to-square text-[var(--gold)]"></i>
                        <span>Buat Postingan Baru</span>
                    </h3>
                    <button onclick="closeCreateModal()" class="text-white/40 hover:text-white">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-white/80 uppercase mb-1">Judul Postingan</label>
                        <input type="text" name="title" required placeholder="Contoh: Dokumen Kegiatan Tahfidz..."
                               class="tsaqib-input w-full px-4 py-2.5 text-xs">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-white/80 uppercase mb-1">Kategori Komunitas</label>
                        <select name="community_slug" required class="tsaqib-input w-full px-4 py-2.5 text-xs">
                            @foreach($daftarKomunitas as $k)
                                <option value="{{ $k['slug'] }}" {{ (Auth::user()->selected_community == $k['slug'] || $currentSlug == $k['slug']) ? 'selected' : '' }}>
                                    {{ $k['nama'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-white/80 uppercase mb-1">Isi Postingan / Deskripsi</label>
                        <textarea name="content" rows="4" required placeholder="Tuliskan materi, pengumuman, atau rincian kegiatan..."
                                  class="tsaqib-input w-full px-4 py-2.5 text-xs"></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-white/80 uppercase mb-1">Lampirkan Foto (Opsional)</label>
                        <input type="file" name="image" accept="image/*"
                               class="w-full text-xs text-white/50 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-[#01795F]/20 file:text-[#3fd6b0] hover:file:bg-[#01795F]/30">
                    </div>

                    <div class="pt-2 flex items-center justify-end space-x-2 border-t border-white/10">
                        <button type="button" onclick="closeCreateModal()" class="px-4 py-2 rounded-xl text-xs font-semibold bg-white/10 text-white/70">Batal</button>
                        <button type="submit" class="px-5 py-2 rounded-xl text-xs font-semibold bg-[#01795F] hover:bg-[#3F704D] text-white shadow-sm">
                            <i class="fa-solid fa-paper-plane mr-1.5"></i>Terbitkan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endauth

    <!-- Footer -->
    @include('partials.site-footer')

    <!-- SCRIPT UNTUK MODAL SAJA -->
    <script>
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
