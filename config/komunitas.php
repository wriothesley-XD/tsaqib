<?php

// config/komunitas.php
// Ganti slug, nama, deskripsi, dan warna_aksen sesuai 13 komunitas asli.
// Slug dipakai di URL: /komunitas/{slug}

return [

    'daftar' => [

        [
            'slug' => 'tahfidz',
            'nama' => 'Komunitas Tahfidz',
            'logo' => 'komunitas/tahfidz.png',
            'warna_aksen' => '#2E7D32',
            'deskripsi_singkat' => 'Komunitas fokus menghafal dan murojaah Al-Qur\'an.',
            'role' => [
                'Ketua Komunitas',
                'Wakil Ketua',
                'Koordinator Halaqah',
            ],
        ],

        [
            'slug' => 'dakwah',
            'nama' => 'Komunitas Dakwah',
            'logo' => 'komunitas/dakwah.png',
            'warna_aksen' => '#1565C0',
            'deskripsi_singkat' => 'Komunitas yang bergerak di bidang syiar dan kajian.',
            'role' => [
                'Ketua Komunitas',
                'Wakil Ketua',
                'Koordinator Kajian',
            ],
        ],

        // TODO: lanjutkan sampai 13 komunitas.
        // Copy salah satu blok di atas, ganti slug/nama/logo/deskripsi/role.

    ],

];
