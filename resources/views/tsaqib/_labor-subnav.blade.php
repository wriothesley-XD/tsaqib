{{-- Sub-navigasi 3 halaman Laboratorium PAI — tab underline kecil, rata kiri.
     Pakai: @include('tsaqib._labor-subnav', ['active' => 'profil']) --}}
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
    <nav class="flex items-center gap-6 border-b border-white/10 overflow-x-auto" aria-label="Navigasi Laboratorium PAI">
        <a href="{{ route('laboratorium.profil') }}"
           class="u-tab {{ $active === 'profil' ? 'is-active' : '' }}">Profil &amp; Guru</a>
        <a href="{{ route('laboratorium.modul') }}"
           class="u-tab {{ $active === 'modul' ? 'is-active' : '' }}">Modul Pembelajaran</a>
        <a href="{{ route('laboratorium.tugas') }}"
           class="u-tab {{ $active === 'tugas' ? 'is-active' : '' }}">Tugas Siswa</a>
    </nav>
</div>
