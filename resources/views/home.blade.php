<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Home</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts & Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <!-- Container Utama: Mengisi 100% tinggi layar monitor (min-h-screen) -->
    <div class="min-h-screen flex flex-col bg-gray-100">

        <!-- Konten Utama Tengah (flex-grow mendorong footer ke paling bawah) -->
        <main class="flex-grow flex items-center justify-center p-6">
            <div class="max-w-3xl w-full bg-white shadow-lg rounded-2xl p-8 text-center border border-gray-100">
                
                <h1 class="text-4xl font-extrabold text-gray-800 mb-3">Selamat Datang di Home</h1>
                <p class="text-gray-600 mb-8 text-lg">Pilih halaman yang ingin Anda kunjungi di bawah ini:</p>

                <!-- Pilihan Tombol-Tombol Navigasi -->
                <div class="flex justify-center gap-4 flex-wrap">
                    <!-- Tombol Home (Aktif - Warna Hijau) -->
                    <a href="{{ route('home') }}" class="px-6 py-3 bg-emerald-600 text-white font-semibold rounded-xl shadow-md hover:bg-emerald-700 transition duration-200">
                    </a>

                    <!-- Tombol Community -->
                    <a href="{{ route('community') }}" class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-xl shadow-md hover:bg-blue-700 transition duration-200">
                        Community
                    </a>

                    <!-- Tombol Dashboard -->
                    <a href="{{ route('dashboard') }}" class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-xl shadow-md hover:bg-blue-700 transition duration-200">
                        Dashboard
                    </a>

                    <!-- Tombol Perpustakaan -->
                    <a href="{{ route('perpustakaan') }}" class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-xl shadow-md hover:bg-blue-700 transition duration-200">
                        Perpustakaan
                    </a>
                </div>

            </div>
        </main>

        <!-- Footer Paling Bawah -->
        <footer class="bg-slate-900 text-slate-300 text-center py-4 border-t border-slate-800">
            <div class="max-w-7xl mx-auto px-4">
                <p class="text-sm">&copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All rights reserved.</p>
            </div>
        </footer>

    </div>
</body>
</html>