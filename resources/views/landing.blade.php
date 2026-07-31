<!-- resources/views/landing.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>TSAQIB - Beranda</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: #eef4f8;
        }
        .pulau-wrapper {
            position: relative;
            max-width: 1000px;
            margin: 40px auto;
        }
        .pulau-wrapper img {
            width: 100%;
            display: block;
        }

        /* Hotspot komunitas — posisi absolut di atas gambar pulau.
           TODO: sesuaikan top/left tiap hotspot pakai coordinate picker
           yang sudah pernah dibuat waktu planning map. */
        .hotspot {
            position: absolute;
            width: 48px;
            height: 48px;
            cursor: pointer;
        }
        .hotspot img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
        }
        .hotspot-placeholder {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 700;
            font-size: 14px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.3);
        }

        .perpustakaan-link {
            display: block;
            text-align: center;
            margin: 24px auto;
            font-weight: 600;
        }
    </style>
</head>
<body>

    <div class="pulau-wrapper">
        {{-- Ganti src dengan ilustrasi pulau ibisPaint kamu --}}
        <img src="{{ asset('images/pulau-tsaqib.png') }}" alt="Pulau TSAQIB">

        @foreach(config('komunitas.daftar') as $komunitas)
            @php
                // Fallback: kalau file logo belum ada, tampilkan lingkaran
                // berwarna aksen dengan inisial nama komunitas.
                $logoPath = public_path($komunitas['logo'] ?? '');
                $logoAda = !empty($komunitas['logo']) && file_exists($logoPath);
                $inisial = collect(explode(' ', $komunitas['nama']))
                    ->map(fn($kata) => mb_substr($kata, 0, 1))
                    ->take(2)
                    ->implode('');
            @endphp
            <a href="{{ route('komunitas.show', $komunitas['slug']) }}"
               class="hotspot"
               style="top: 0px; left: 0px;"
               title="{{ $komunitas['nama'] }}">
                @if($logoAda)
                    <img src="{{ asset($komunitas['logo']) }}" alt="{{ $komunitas['nama'] }}">
                @else
                    <div class="hotspot-placeholder"
                         style="background: {{ $komunitas['warna_aksen'] ?? '#1565C0' }};">
                        {{ strtoupper($inisial) }}
                    </div>
                @endif
            </a>
        @endforeach
    </div>

    <a class="perpustakaan-link" href="{{ route('perpustakaan') }}">
        Masuk ke Perpustakaan FSI &rarr;
    </a>

</body>
</html>
