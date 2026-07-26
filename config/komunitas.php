<?php

// Data semua komunitas FSI. Ini SATU-SATUNYA sumber data yang dipakai
// oleh Blade (render hotspot & popup) dan JS (state machine).
//
// Ukuran gambar map: 2406 x 2406 px (peta-pulau.webp, hasil export
// layer "hires 1" dari Figma).
//
// Koordinat 'posisi' (x, y) di bawah ini diambil langsung dengan klik
// di atas gambar yang sudah di-export, jadi SUDAH final — tidak perlu
// ditambah offset apa pun lagi.
//
// Kalau ada komunitas yang posisinya masih meleset dikit, tinggal
// re-klik pakai alat picker dan update angkanya di sini.

return [

    'tahfidz' => [
        'nama'       => 'Tahfidz',
        'deskripsi'  => 'Komunitas hafalan Al-Quran.',
        'icon'       => 'icons/tahfidz.svg',
        'posisi'     => ['x' => 1932, 'y' => 1376],
        'aktif'      => true,
        'roles'      => [
            'hafidz'   => ['nama' => 'Hafidz/Hafidzah'],
            'pengajar' => ['nama' => 'Pengajar'],
        ],
    ],

    'the-yours' => [
        'nama'       => 'The Yours',
        'deskripsi'  => '',
        'icon'       => 'icons/the-yours.svg',
        'posisi'     => ['x' => 2046, 'y' => 1225],
        'aktif'      => true,
        'roles'      => [],
    ],

    'growup' => [
        'nama'       => 'Growup',
        'deskripsi'  => '',
        'icon'       => 'icons/growup.svg',
        'posisi'     => ['x' => 2296, 'y' => 1063],
        'aktif'      => true,
        'roles'      => [],
    ],

    'leora' => [
        'nama'       => 'Leora',
        'deskripsi'  => '',
        'icon'       => 'icons/leora.svg',
        'posisi'     => ['x' => 983, 'y' => 1927],
        'aktif'      => true,
        'roles'      => [],
    ],

    'gofam' => [
        'nama'       => 'Gofam',
        'deskripsi'  => '',
        'icon'       => 'icons/gofam.svg',
        'posisi'     => ['x' => 852, 'y' => 1756],
        'aktif'      => true,
        'roles'      => [],
    ],

    'mushou' => [
        'nama'       => 'Mushou',
        'deskripsi'  => '',
        'icon'       => 'icons/mushou.svg',
        'posisi'     => ['x' => 1646, 'y' => 1800],
        'aktif'      => true,
        'roles'      => [],
    ],

    'blitzsport' => [
        'nama'       => 'Blitzsport',
        'deskripsi'  => '',
        'icon'       => 'icons/blitzsport.svg',
        'posisi'     => ['x' => 1431, 'y' => 1949],
        'aktif'      => true,
        'roles'      => [],
    ],

    'tsaqib-merch' => [
        'nama'       => 'Tsaqib Merch',
        'deskripsi'  => '',
        'icon'       => 'icons/tsaqib-merch.svg',
        'posisi'     => ['x' => 1225, 'y' => 290],
        'aktif'      => true,
        'roles'      => [],
    ],

    'tsaqib-community' => [
        'nama'       => 'Tsaqib Community',
        'deskripsi'  => '',
        'icon'       => 'icons/tsaqib-community.svg',
        'posisi'     => ['x' => 1238, 'y' => 2099],
        'aktif'      => true,
        'roles'      => [],
    ],

    'tsaqib-press' => [
        'nama'       => 'Tsaqib Press',
        'deskripsi'  => '',
        'icon'       => 'icons/tsaqib-press.svg',
        'posisi'     => ['x' => 2169, 'y' => 667],
        'aktif'      => true,
        'roles'      => [],
    ],

    'tsaqib-media' => [
        'nama'       => 'Tsaqib Media',
        'deskripsi'  => '',
        'icon'       => 'icons/tsaqib-media.svg',
        'posisi'     => ['x' => 285, 'y' => 593],
        'aktif'      => true,
        'roles'      => [],
    ],

    'tsaqib-center' => [
        'nama'       => 'Tsaqib Center',
        'deskripsi'  => '',
        'icon'       => 'icons/tsaqib-center.svg',
        'posisi'     => ['x' => 1221, 'y' => 975],
        'aktif'      => true,
        'roles'      => [],
    ],

    // Land yang belum tersedia tahun ini. Slug dan nama masih generik
    // (land-kosong-1, dst) karena belum jelas ini calon komunitas apa —
    // ganti nama/slug-nya begitu tim sudah menentukan.
    'land-kosong-1' => [
        'nama'       => 'Land Belum Tersedia',
        'deskripsi'  => '',
        'icon'       => 'icons/land-kosong.svg',
        'posisi'     => ['x' => 1607, 'y' => 615],
        'aktif'      => false,
        'roles'      => [],
    ],

    'land-kosong-2' => [
        'nama'       => 'Land Belum Tersedia',
        'deskripsi'  => '',
        'icon'       => 'icons/land-kosong.svg',
        'posisi'     => ['x' => 834, 'y' => 588],
        'aktif'      => false,
        'roles'      => [],
    ],

    'land-kosong-3' => [
        'nama'       => 'Land Belum Tersedia',
        'deskripsi'  => '',
        'icon'       => 'icons/land-kosong.svg',
        'posisi'     => ['x' => 496, 'y' => 926],
        'aktif'      => false,
        'roles'      => [],
    ],

    'land-kosong-4' => [
        'nama'       => 'Land Belum Tersedia',
        'deskripsi'  => '',
        'icon'       => 'icons/land-kosong.svg',
        'posisi'     => ['x' => 105, 'y' => 1111],
        'aktif'      => false,
        'roles'      => [],
    ],

    'land-kosong-5' => [
        'nama'       => 'Land Belum Tersedia',
        'deskripsi'  => '',
        'icon'       => 'icons/land-kosong.svg',
        'posisi'     => ['x' => 342, 'y' => 1238],
        'aktif'      => false,
        'roles'      => [],
    ],

    'land-kosong-6' => [
        'nama'       => 'Land Belum Tersedia',
        'deskripsi'  => '',
        'icon'       => 'icons/land-kosong.svg',
        'posisi'     => ['x' => 461, 'y' => 1444],
        'aktif'      => false,
        'roles'      => [],
    ],

];
