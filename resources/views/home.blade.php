<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts & Styling Tailwind CSS -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <!-- Kombinasi min-h-screen dan flex flex-col agar footer menempel paling bawah -->
        <div class="min-h-screen flex flex-col bg-gray-100">
            
            <!-- Navbar Atas Bawaan Breeze -->
            @include('layouts.navigation')

            <!-- Page Heading (Jika Ada Header Dinamis) -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content (flex-grow akan mendorong footer ke dasar halaman) -->
            <main class="flex-grow">
                {{ $slot ?? '' }}
                @yield('content')
            </main>

            <!-- Sticky Footer Paling Bawah -->
            <footer class="bg-slate-800 text-white text-center py-4 border-t border-slate-700">
                <div class="max-w-7xl mx-auto px-4">
                    <p>&copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All rights reserved.</p>
                </div>
            </footer>

        </div>
    </body>
</html>