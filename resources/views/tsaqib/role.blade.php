{{-- resources/views/tsaqib/role.blade.php --}}
{{-- TODO: integrasikan ke layout utama (@extends) begitu tim desain fix-in template. --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Informasi Role</title>
</head>
<body>
    <h1>Informasi Role</h1>

    @forelse ($roles as $item)
        <div>
            <h2>{{ $item['jabatan'] }}</h2>
            <p>{{ $item['tugas'] }}</p>
        </div>
    @empty
        <p>Belum ada data role.</p>
    @endforelse
</body>
</html>
