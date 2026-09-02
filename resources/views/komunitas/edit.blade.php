{{-- resources/views/komunitas/edit.blade.php — halaman edit postingan (pengganti modal).
     Simpel: 1 baris back-link, form judul/konten/media, tombol simpan & batal.
     Tanpa JS — hapus media via checkbox native, validasi plafon di server. --}}
@php($pageTitle = 'Edit Postingan - Komunitas TSAQIB')
@php($komunitas = collect(config('komunitas.daftar'))->firstWhere('slug', $post->community_slug))
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    @include('partials.theme-head')
</head>
<body class="text-[var(--cream)] font-sans antialiased min-h-screen flex flex-col relative overflow-x-hidden">

    <!-- Unified TSAQIB Navbar -->
    @include('partials.navbar')

    <main class="flex-1 max-w-2xl mx-auto px-4 sm:px-6 py-8 sm:py-10 w-full">

        {{-- Back-link: balik ke halaman detail post (sama tujuan dgn redirect setelah simpan) --}}
        <a href="{{ route('komunitas.post.show', $post->id) }}"
           class="inline-flex items-center gap-1.5 text-xs font-semibold text-white/50 hover:text-[var(--gold)] transition mb-4">
            <i class="fa-solid fa-arrow-left"></i>
            Kembali ke {{ $komunitas['nama'] ?? 'Detail Postingan' }}
        </a>

        <div class="tsaqib-card p-6 sm:p-8">

            <h1 class="font-display font-bold text-xl text-[var(--cream)] flex items-center gap-2 mb-1">
                <i class="fa-solid fa-pen text-[var(--gold)]"></i> Edit Postingan
            </h1>
            <p class="text-white/40 text-xs mb-6">Perbarui judul, isi, atau media postingan Anda.</p>

            @if (session('error'))
                <div class="mb-4 p-3 rounded-xl bg-red-500/10 text-red-300 border border-red-500/30 text-xs font-semibold">
                    {{ session('error') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="mb-4 p-3 rounded-xl bg-red-500/10 text-red-300 border border-red-500/30 text-xs font-semibold">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('komunitas.post.update', $post->id) }}" method="POST" enctype="multipart/form-data"
                  class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label for="title" class="block text-xs font-bold text-white/80 uppercase mb-1">Judul Postingan</label>
                    <input type="text" id="title" name="title" value="{{ old('title', $post->title) }}" required
                           placeholder="Judul postingan..." class="tsaqib-input w-full px-4 py-2.5 text-sm">
                </div>

                <div>
                    <label for="content" class="block text-xs font-bold text-white/80 uppercase mb-1">Isi Postingan / Deskripsi</label>
                    <textarea id="content" name="content" rows="6" required
                              placeholder="Tuliskan materi, pengumuman, atau rincian kegiatan..."
                              class="tsaqib-input w-full px-4 py-2.5 text-sm">{{ old('content', $post->content) }}</textarea>
                </div>

                @if ($post->media->count())
                    <div>
                        <label class="block text-xs font-bold text-white/80 uppercase mb-2">Media Saat Ini <span class="normal-case font-medium text-white/40">(klik foto = tandai hapus)</span></label>
                        <div class="grid grid-cols-3 sm:grid-cols-4 gap-2">
                            @foreach ($post->media as $m)
                                <label class="relative block aspect-square rounded-xl overflow-hidden border border-white/10 bg-white/5 cursor-pointer">
                                    @if ($m->type === 'video')
                                        <video src="{{ $m->url }}" muted playsinline class="w-full h-full object-cover"></video>
                                        <span class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                            <i class="fa-solid fa-film text-[var(--gold)] text-lg"></i>
                                        </span>
                                    @else
                                        <img src="{{ $m->url }}" alt="{{ $post->title }}" loading="lazy" class="w-full h-full object-cover">
                                    @endif
                                    {{-- Checkbox hapus: dicentang = item dihapus saat simpan --}}
                                    <input type="checkbox" name="remove_media[]" value="{{ $m->id }}"
                                           class="absolute inset-0 opacity-0 cursor-pointer">
                                    <span class="remove-badge absolute top-1.5 right-1.5 w-6 h-6 rounded-full bg-black/60 border border-white/20 text-white/70 flex items-center justify-center text-[10px] pointer-events-none transition">
                                        <i class="fa-solid fa-xmark"></i>
                                    </span>
                                    <span class="remove-hint absolute inset-x-0 bottom-0 bg-black/55 text-[9px] font-bold text-center py-1 text-white/80 pointer-events-none transition">Hapus saat simpan</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div>
                    <label class="block text-xs font-bold text-white/80 uppercase mb-1">Tambah Media Baru (Opsional)</label>
                    <div class="media-drop">
                        <label class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg bg-[#01795F]/15 hover:bg-[#01795F]/25 text-[#3fd6b0] text-xs font-semibold cursor-pointer border border-[#01795F]/30 transition">
                            <i class="fa-solid fa-plus"></i> Tambahkan Foto/Video
                            <input type="file" name="media[]" multiple
                                   accept="image/jpeg,image/png,image/webp,video/mp4,video/webm" class="sr-only">
                        </label>
                        <div class="media-count mt-2">Maks 6 foto (jpg/png/webp, ≤3 MB) atau 1 video (mp4/webm, ≤30 MB). Tidak boleh campur.</div>
                    </div>
                </div>

                <div class="pt-2 flex items-center justify-end gap-2 border-t border-white/10">
                    <a href="{{ route('komunitas.post.show', $post->id) }}"
                       class="px-4 py-2 rounded-xl text-xs font-semibold bg-white/10 text-white/70 hover:text-white transition">Batal</a>
                    <button type="submit"
                            class="px-5 py-2 rounded-xl text-xs font-semibold bg-[#01795F] hover:bg-[#3F704D] text-white shadow-sm transition">
                        <i class="fa-solid fa-check mr-1.5"></i>Simpan Perubahan
                    </button>
                </div>
            </form>

        </div>
    </main>

    @include('partials.site-footer')

    @push('styles')
    <style>
        /* Status "ditandai hapus" pada media lama — CSS saja, tanpa JS:
           checkbox (input peer) dicentang -> badge jadi X merah + hint aktif. */
        .media-drop{ border:1px dashed rgba(247,245,239,.2); border-radius:.75rem; padding:.6rem; }
        .media-count{ font-size:10px; color:rgba(247,245,239,.5); }
    </style>
    @endpush

    <script>
        /* Tandai visual media yang dicentang utk dihapus (badge X merah + overlay). */
        document.querySelectorAll('input[name="remove_media[]"]').forEach((cb) => {
            cb.addEventListener('change', () => {
                const tile = cb.closest('label');
                tile.querySelector('.remove-badge')?.classList.toggle('!bg-red-500', cb.checked);
                tile.querySelector('.remove-hint')?.classList.toggle('!bg-red-500/80', cb.checked);
                tile.querySelector('img,video')?.classList.toggle('opacity-30', cb.checked);
            });
        });
    </script>

</body>
</html>
