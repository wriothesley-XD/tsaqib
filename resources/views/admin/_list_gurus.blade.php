{{-- Baris tabel Guru Pengampu — dipakai di tab (hal. 1) maupun endpoint AJAX (hal. N). --}}
@forelse($gurus as $index => $guru)
    @php
        $payload = [
            'id' => $guru->id,
            'nama' => $guru->nama,
            'nip' => $guru->nip,
            'mapel_pengampu' => $guru->mapel_pengampu,
            'kelas_diampu' => $guru->kelas_diampu ?? [],
            'deskripsi' => $guru->deskripsi,
            'facebook_url' => $guru->facebook_url,
            'instagram_url' => $guru->instagram_url,
            'email' => $guru->email,
            'wa_number' => $guru->wa_number,
        ];
    @endphp
    <tr class="hover:bg-white/5">
        <td data-label="#" class="p-3 font-bold">{{ $startIndex + $index }}</td>
        <td data-label="Foto" class="p-3">
            @if($guru->foto_path)
                <img src="{{ asset('storage/' . $guru->foto_path) }}" alt="{{ $guru->nama }}" class="w-12 h-12 rounded-xl object-cover border border-white/10 shadow-sm">
            @else
                <div class="w-12 h-12 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-[var(--gold)]">
                    <i class="fa-solid fa-chalkboard-user text-base"></i>
                </div>
            @endif
        </td>
        <td data-label="Nama Guru" class="p-3">
            <span class="font-bold text-[var(--cream)] block text-sm">{{ $guru->nama }}</span>
            @if($guru->nip)
                <span class="text-[11px] text-white/45 font-mono block">NIP. {{ $guru->nip }}</span>
            @endif
        </td>
        <td data-label="Mapel & Kelas" class="p-3">
            <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold bg-[var(--gold)]/15 text-[var(--gold)] border border-[var(--gold)]/25 mb-1">
                {{ $guru->mapel_pengampu ?? 'Pendidikan Agama Islam' }}
            </span>
            @if(!empty($guru->kelas_diampu))
                <div class="flex flex-wrap gap-1">
                    @foreach($guru->kelas_diampu as $kelas)
                        <span class="px-1.5 py-0.2 rounded text-[9px] font-bold bg-[#01795F]/15 text-[#3fd6b0] border border-[#01795F]/25">
                            Kelas {{ $kelas }}
                        </span>
                    @endforeach
                </div>
            @endif
        </td>
        <td data-label="Deskripsi" class="p-3">
            <span class="text-[11px] text-white/60 block max-w-xs line-clamp-2">
                {{ $guru->deskripsi ?: 'Belum ada deskripsi profil.' }}
            </span>
        </td>
        <td data-label="Media Sosial" class="p-3">
            <div class="flex items-center gap-2">
                @if($guru->facebook_url)
                    <a href="{{ $guru->facebook_url }}" target="_blank" rel="noopener noreferrer" class="w-7 h-7 rounded-lg bg-blue-500/15 border border-blue-500/30 text-blue-400 flex items-center justify-center text-xs hover:bg-blue-500 hover:text-white transition" title="Facebook">
                        <i class="fa-brands fa-facebook-f"></i>
                    </a>
                @else
                    <span class="w-7 h-7 rounded-lg bg-white/5 border border-white/10 text-white/20 flex items-center justify-center text-xs" title="Facebook belum diisi">
                        <i class="fa-brands fa-facebook-f"></i>
                    </span>
                @endif

                @if($guru->instagram_url)
                    @php($igLink = str_starts_with($guru->instagram_url, 'http') ? $guru->instagram_url : 'https://instagram.com/' . ltrim($guru->instagram_url, '@'))
                    <a href="{{ $igLink }}" target="_blank" rel="noopener noreferrer" class="w-7 h-7 rounded-lg bg-pink-500/15 border border-pink-500/30 text-pink-400 flex items-center justify-center text-xs hover:bg-pink-500 hover:text-white transition" title="Instagram">
                        <i class="fa-brands fa-instagram"></i>
                    </a>
                @else
                    <span class="w-7 h-7 rounded-lg bg-white/5 border border-white/10 text-white/20 flex items-center justify-center text-xs" title="Instagram belum diisi">
                        <i class="fa-brands fa-instagram"></i>
                    </span>
                @endif
            </div>
        </td>
        <td data-label="Kontak" class="p-3">
            <div class="space-y-0.5 text-[11px]">
                @if($guru->wa_number)
                    <a href="https://wa.me/{{ preg_replace('/\D/', '', $guru->wa_number) }}" target="_blank" rel="noopener" class="text-[#3fd6b0] hover:underline flex items-center gap-1 font-semibold">
                        <i class="fa-brands fa-whatsapp text-xs"></i>
                        <span>{{ $guru->wa_number }}</span>
                    </a>
                @endif
                @if($guru->email)
                    <a href="mailto:{{ $guru->email }}" class="text-[var(--gold)] hover:underline flex items-center gap-1">
                        <i class="fa-solid fa-envelope text-[10px]"></i>
                        <span>{{ $guru->email }}</span>
                    </a>
                @endif
                @if(!$guru->wa_number && !$guru->email)
                    <span class="text-white/30 italic">-</span>
                @endif
            </div>
        </td>
        <td data-label="Aksi" class="p-3">
            <div class="flex items-center gap-2">
                <button type="button" data-guru-edit
                        data-payload="{{ json_encode($payload) }}"
                        class="text-xs text-sky-300 hover:text-sky-200 font-bold flex items-center gap-1 cursor-pointer">
                    <i class="fa-solid fa-pen-to-square"></i>
                    <span>Edit</span>
                </button>
                <form action="{{ route('admin.gurus.destroy', $guru->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus profil guru {{ addslashes($guru->nama) }}?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-xs text-red-400 hover:text-red-300 font-bold p-1 cursor-pointer">
                        <i class="fa-solid fa-trash"></i>
                        <span>Hapus</span>
                    </button>
                </form>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="8" class="p-6 text-center text-white/40">
            @if(!empty($search))
                Tidak ada guru yang cocok dengan <span class="text-white/70 font-semibold">"{{ $search }}"</span>.
            @else
                Belum ada data guru pengampu. Silakan tambahkan profil guru baru melalui tombol di atas.
            @endif
        </td>
    </tr>
@endforelse
