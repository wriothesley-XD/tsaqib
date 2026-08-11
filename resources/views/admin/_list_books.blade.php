{{-- Baris tabel Buku — dipakai di tab (hal. 1) maupun endpoint AJAX (hal. N). --}}
@forelse($books as $index => $book)
    @php
        $payload = [
            'id'          => $book->id,
            'title'       => $book->title,
            'author'      => $book->author,
            'category'    => $book->category,
            'description' => $book->description,
            'is_visible'  => (bool) $book->is_visible,
        ];
    @endphp
    <tr class="hover:bg-white/5">
        <td data-label="#" class="p-3 font-bold">{{ $startIndex + $index }}</td>
        <td data-label="Cover" class="p-3">
            @if($book->cover_image)
                <img src="{{ asset('storage/' . $book->cover_image) }}" alt="" class="w-10 h-14 rounded-md object-cover border border-white/10">
            @else
                <div class="w-10 h-14 rounded-md bg-white/5 border border-white/10 flex items-center justify-center text-white/25">
                    <i class="fa-solid fa-file-pdf text-sm"></i>
                </div>
            @endif
        </td>
        <td data-label="Judul" class="p-3 font-semibold text-[var(--cream)]">{{ $book->title }}</td>
        <td data-label="Penulis" class="p-3 text-white/60">{{ $book->author }}</td>
        <td data-label="Kategori" class="p-3 font-bold text-[var(--gold)] uppercase">{{ $book->category }}</td>
        <td data-label="Status" class="p-3">
            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-[#01795F]/15 text-[#3fd6b0]">Aktif Tampil</span>
        </td>
        <td data-label="File PDF" class="p-3">
            @if($book->pdf_path)
                <a href="{{ asset('storage/' . $book->pdf_path) }}" target="_blank" class="text-[var(--gold)] font-bold hover:underline">
                    <i class="fa-solid fa-file-pdf mr-1"></i>Lihat PDF
                </a>
            @else
                <span class="text-white/40">-</span>
            @endif
        </td>
        <td data-label="Aksi" class="p-3">
            <div class="flex items-center gap-2 flex-wrap">
                <button type="button" data-book-edit
                        data-payload="{{ json_encode($payload) }}"
                        class="text-xs text-sky-300 hover:text-sky-200 font-bold">
                    <i class="fa-solid fa-pen-to-square"></i> Edit
                </button>
                <form action="{{ route('admin.books.destroy', $book->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus buku ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-xs text-red-400 hover:text-red-300 font-bold p-1">
                        <i class="fa-solid fa-trash"></i> Hapus
                    </button>
                </form>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="8" class="p-6 text-center text-white/40">
            @if(!empty($search))
                Tidak ada buku yang cocok dengan <span class="text-white/70 font-semibold">"{{ $search }}"</span>.
            @else
                Belum ada koleksi buku digital. Klik tombol di atas untuk menambah buku baru.
            @endif
        </td>
    </tr>
@endforelse
