<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Komunitas</title>
    <style>
      /* 1. Atur body menjadi Flexbox vertikal dengan tinggi minimal 100vh */
      body {
        margin: 0;
        display: flex;
        flex-direction: column;
        min-height: 100vh;
        font-family: sans-serif;
      }

      /* 2. Main content mengambil seluruh sisa ruang kosong */
      main {
        flex: 1;
        padding: 20px;
      }

      /* 3. Footer secara otomatis berada di paling bawah */
      footer {
        background-color: #1e293b;
        color: white;
        text-align: center;
        padding: 15px;
      }
    </style>
</head>
<body>

    {{-- Place Navbar at the top --}}
    @include('partials.navbar')

    {{-- Wrap all main content inside <main> --}}
    <main>
      <h1>Laman Komunitas</h1>
      <p>Ini adalah halaman untuk diskusi dan informasi komunitas.</p>
      
      <!-- Tambahkan konten komunitas lainnya di sini -->
    </main>

    {{-- Footer strictly at the bottom --}}
    <footer>
      <p>&copy; 2026 Website Saya - Hak Cipta Dilindungi</p>
    </footer>

</body>
</html>