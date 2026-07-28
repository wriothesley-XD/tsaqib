<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda</title>
</head>
<body>

    @include('partials.navbar')
    
    <h1>Halo, {{ $nama }}!</h1>
    <p>Tanggal: {{ date('d-m-Y') }}</p>
    <p>Selamat datang di Halaman Utama.</p>

</body>
</html>