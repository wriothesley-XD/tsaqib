{{-- Tab: Buku PDF (direlokasi) --}}
<div class="tsaqib-card p-6">
    <div class="flex items-center justify-between mb-6 pb-4 border-b border-white/10">
        <div>
            <h3 class="font-display font-bold text-[var(--cream)] text-base flex items-center space-x-2">
                <i class="fa-solid fa-book text-[var(--gold)]"></i>
                <span>Kelola Buku PDF Perpustakaan ({{ count($books) }})</span>
            </h3>
            <p class="text-xs text-white/50 mt-0.5">Tambah, edit, dan hapus koleksi buku digital FSI</p>
        </div>
        <button onclick="document.getElementById('add-book-form').classList.toggle('hidden')"
                class="px-4 py-2 rounded-xl bg-[#01795F] text-white text-xs font-semibold shadow-sm hover:bg-[#3F704D] transition">
            + Tambah Buku PDF Baru
        </button>
    </div>

    <!-- FORM TAMBAH BUKU (HIDDEN BY DEFAULT) -->
    <div id="add-book-form" class="hidden mb-6 p-5 rounded-xl bg-white/5 border border-white/10 space-y-4">
        <h4 class="font-bold text-xs text-[var(--cream)] uppercase tracking-wider">Form Tambah Buku Digital Baru</h4>
        <form action="{{ route('admin.books.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div>
                    <label class="block text-[10px] font-bold uppercase text-white/60 mb-1">Judul Buku *</label>
                    <input type="text" name="title" required class="tsaqib-input w-full px-3 py-2 text-xs">
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase text-white/60 mb-1">Penulis *</label>
                    <input type="text" name="author" required class="tsaqib-input w-full px-3 py-2 text-xs">
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase text-white/60 mb-1">Kategori *</label>
                    <select name="category" required class="tsaqib-input w-full px-3 py-2 text-xs">
                        <option value="fiqih">Fiqih</option>
                        <option value="aqidah">Aqidah</option>
                        <option value="ski">SKI</option>
                        <option value="hadits">Hadits & Tafsir</option>
                        <option value="modul" selected>Modul PAI</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-bold uppercase text-white/60 mb-1">Deskripsi Singkat</label>
                <textarea name="description" rows="2" class="tsaqib-input w-full px-3 py-2 text-xs"></textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-[10px] font-bold uppercase text-white/60 mb-1">Upload File PDF Buku *</label>
                    <input type="file" name="pdf" accept="application/pdf" required class="w-full text-xs text-white/50 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[11px] file:font-semibold file:bg-[#01795F]/20 file:text-[#3fd6b0]">
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase text-white/60 mb-1">Upload Gambar Cover (Opsional)</label>
                    <input type="file" name="cover" accept="image/*" class="w-full text-xs text-white/50 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[11px] file:font-semibold file:bg-[#01795F]/20 file:text-[#3fd6b0]">
                </div>
            </div>

            <div class="flex items-center justify-between pt-2">
                <label class="inline-flex items-center space-x-2">
                    <input type="checkbox" name="is_visible" value="1" checked class="rounded border-white/20 bg-white/5 text-[#01795F]">
                    <span class="text-xs text-white/70">Tampilkan di Perpustakaan</span>
                </label>

                <button type="submit" class="px-5 py-2 rounded-xl bg-[#01795F] text-white font-bold text-xs shadow-sm">
                    Simpan Buku PDF
                </button>
            </div>
        </form>
    </div>

    <!-- TABEL BUKU -->
    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs text-white/75">
            <thead class="bg-white/5 border-b border-white/10 font-bold uppercase text-[10px] text-white/50">
                <tr>
                    <th class="p-3">#</th>
                    <th class="p-3">Judul Buku</th>
                    <th class="p-3">Penulis</th>
                    <th class="p-3">Kategori</th>
                    <th class="p-3">Status</th>
                    <th class="p-3">File PDF</th>
                    <th class="p-3">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/10">
                @forelse($books as $index => $book)
                    <tr class="hover:bg-white/5">
                        <td class="p-3 font-bold">{{ $index + 1 }}</td>
                        <td class="p-3 font-semibold text-[var(--cream)]">{{ $book->title }}</td>
                        <td class="p-3 text-white/60">{{ $book->author }}</td>
                        <td class="p-3 font-bold text-[var(--gold)] uppercase">{{ $book->category }}</td>
                        <td class="p-3">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-[#01795F]/15 text-[#3fd6b0]">Aktif Tampil</span>
                        </td>
                        <td class="p-3">
                            @if($book->pdf_path)
                                <a href="{{ asset('storage/' . $book->pdf_path) }}" target="_blank" class="text-[var(--gold)] font-bold hover:underline">
                                    <i class="fa-solid fa-file-pdf mr-1"></i>Lihat PDF
                                </a>
                            @else
                                <span class="text-white/40">-</span>
                            @endif
                        </td>
                        <td class="p-3">
                            <form action="{{ route('admin.books.destroy', $book->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus buku ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs text-red-400 hover:text-red-300 font-bold p-1">
                                    <i class="fa-solid fa-trash"></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-6 text-center text-white/40">Belum ada koleksi buku digital. Klik tombol di atas untuk menambah buku baru.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
