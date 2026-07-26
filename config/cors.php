<?php

// Config ini WAJIB ada supaya request dari domain Framer
// (misal xxxx.framer.app atau domain custom kalian) tidak diblokir
// browser gara-gara beda origin dengan domain Laravel.
//
// Kalau file config/cors.php ini sudah ada di project (biasanya bawaan
// Laravel), tinggal sesuaikan bagian 'allowed_origins' saja -- tidak
// perlu ganti seluruh file.

return [

    'paths' => ['api/*'],

    'allowed_methods' => ['*'],

    // Ganti '*' dengan domain Framer yang sebenarnya begitu sudah ada
    // (lebih aman daripada '*' yang mengizinkan semua domain).
    // Contoh: ['https://tsaqib-island.framer.website']
    //
    // Selama masih development/testing pakai ngrok, '*' boleh dipakai
    // dulu biar tidak ribet ganti-ganti tiap URL ngrok berubah.
    'allowed_origins' => ['*'],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,

];
