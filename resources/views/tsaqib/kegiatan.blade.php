{{-- resources/views/tsaqib/kegiatan.blade.php --}}
{{-- TODO: integrasikan ke layout utama (@extends) begitu tim desain fix-in template. --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Informasi Kegiatan FSI</title>
</head>
<body>
    <h1>Informasi Kegiatan FSI</h1>

    @forelse ($kegiatan as $item)
        <div>
            <h2>{{ $item['nama'] }}</h2>
            <p>{{ $item['tanggal'] ?? 'Tanggal belum ditentukan' }}</p>
            <p>{{ $item['deskripsi'] }}</p>
        </div>
    @empty
        <p>Belum ada kegiatan.</p>
    @endforelse
</body>
</html>
