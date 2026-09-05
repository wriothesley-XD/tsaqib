<?php

/*
|------------------------------------------------------------------------------
| Whitelist NISN siswa — Pintu A verifikasi siswa.
|------------------------------------------------------------------------------
| NISN yang ada di sini otomatis terverifikasi tanpa upload foto KTS.
| NISN di luar daftar jatuh ke Pintu B: upload foto KTS → approval admin.
|
| ponytail: file statis; ganti ke tabel nisn_whitelist + CRUD admin
| bila daftarnya besar atau berubah tiap tahun ajaran.
*/

return [
    'whitelist' => [
        // '0059123456',
        // '0059654321',
    ],
];
