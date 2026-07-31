<!-- resources/views/komunitas/show.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $komunitas['nama'] }} - TSAQIB</title>
    <style>
        :root {
            --aksen: {{ $komunitas['warna_aksen'] ?? '#1565C0' }};
        }
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            color: #1a1a1a;
        }
        nav {
            background: var(--aksen);
            padding: 16px 32px;
            display: flex;
            gap: 24px;
        }
        nav a {
            color: #fff;
            text-decoration: none;
            font-weight: 600;
        }
        nav a:hover {
            text-decoration: underline;
        }
        header {
            padding: 32px;
            border-bottom: 1px solid #eee;
        }
        header img {
            height: 64px;
            margin-bottom: 12px;
        }
        main {
            padding: 32px;
        }
        footer {
            text-align: center;
            padding: 24px;
            color: #777;
            font-size: 14px;
        }
    </style>
</head>
<body>

    <nav>
        <a href="{{ route('landing') }}">Beranda</a>
        <a href="{{ route('komunitas.show', $komunitas['slug']) }}">Komunitas</a>
        <a href="{{ route('laboratorium.pai') }}">Laboratorium PAI</a>
        <a href="{{ route('informasi.kegiatan') }}">Informasi Kegiatan</a>
        <a href="{{ route('perpustakaan') }}">Perpustakaan</a>
        <a href="{{ route('open.recruitment') }}">Open Recruitment</a>
    </nav>

    <header>
        @php
            $logoPath = public_path($komunitas['logo'] ?? '');
            $logoAda = !empty($komunitas['logo']) && file_exists($logoPath);
        @endphp
        @if($logoAda)
            <img src="{{ asset($komunitas['logo']) }}" alt="Logo {{ $komunitas['nama'] }}">
        @endif
        <h1>{{ $komunitas['nama'] }}</h1>
        <p>{{ $komunitas['deskripsi_singkat'] }}</p>
    </header>

    <main>
        <h2>Role di Komunitas Ini</h2>
        <ul>
            @foreach($komunitas['role'] as $peran)
                <li>{{ $peran }}</li>
            @endforeach
        </ul>

        {{-- TODO: tambahkan konten lain sesuai kebutuhan tiap komunitas --}}
    </main>

    <footer>
        &copy; {{ date('Y') }} TSAQIB - FSI SMAN 1 Bukittinggi
    </footer>

</body>
</html>
