<?php

// config/komunitas.php
// Data 13 Komunitas Resmi FSI (Forum Studi Islam) SMAN 1 Bukittinggi
// Digunakan secara universal oleh template Blade `resources/views/komunitas/show.blade.php`

return [

    'daftar' => [

        [
            'slug' => 'tahfidz',
            'nama' => 'Komunitas Tahfidz',
            'motto' => 'Menjaga Kalam Ilahi, Membumikan Al-Qur\'an di SMANSA',
            'logo' => 'images/icon/growup.svg.png',
            'warna_aksen' => '#059669', // Emerald
            'deskripsi_singkat' => 'Komunitas penjelajah dan penghafal Al-Qur\'an dengan metode talaqqi dan murojaah terstruktur.',
            'deskripsi_lengkap' => 'Komunitas Tahfidz FSI SMAN 1 Bukittinggi wadah berkumpulnya para pemburu mahkota syurga. Melalui halaqah rutin, bimbingan tajwid, dan tasmi\' akbar, anggota dilatih menjaga kelestarian hafalan di tengah kesibukan akademik sekolah.',
            'role' => [
                'Ketua Komunitas Tahfidz',
                'Wakil Ketua & Sie Kurikulum',
                'Koordinator Halaqah Ikhwan',
                'Koordinator Halaqah Akhwat',
                'Penanggung Jawab Tasmi\' Akbar',
            ],
            'kegiatan_rutin' => [
                'Halaqah Subuh & Murojaah Harian',
                'Setoran Pekanan & Bimbingan Tajwid',
                'Tasmi\' Al-Qur\'an Sekali Duduk',
                'Karantina Tahfidz Libur Semester',
            ],
        ],

        [
            'slug' => 'dakwah',
            'nama' => 'Komunitas Dakwah & Syiar',
            'motto' => 'Menyebar Cahaya Kebaikan, Menginspirasi Generasi Rabbani',
            'logo' => 'images/icon/tsaqib-press.svg.png',
            'warna_aksen' => '#2563EB', // Royal Blue
            'deskripsi_singkat' => 'Garda terdepan penyelenggara syiar Islam, kajian rutin, dan tabligh akbar sekolah.',
            'deskripsi_lengkap' => 'Komunitas Dakwah mengelola program kajian inspiratif sekolah, mading syiar Islam, buletin Jumat, serta Safari Masjid. Komunitas ini melatih keberanian berorasi, penyusunan materi dakwah, dan kepemimpinan syiar Islam.',
            'role' => [
                'Ketua Komunitas Dakwah',
                'Koordinator Syiar & Tabligh',
                'Sie Acara Kajian Rutin',
                'Koordinator Mading & Poster Syiar',
                'Penanggung Jawab Safari Masjid',
            ],
            'kegiatan_rutin' => [
                'Kajian Senja Pekanan',
                'Tabligh Akbar Hari Besar Islam',
                'Penerbitan Mading & Risalah Jumat',
                'Kajian Tematik Kepemudaan',
            ],
        ],

        [
            'slug' => 'nasyid',
            'nama' => 'Komunitas Nasyid & Seni Islam',
            'motto' => 'Harmoni Nada Syiar, Menyentuh Hati Melalui Karya',
            'logo' => 'images/icon/mushou.svg.png',
            'warna_aksen' => '#D97706', // Amber Gold
            'deskripsi_singkat' => 'Pengembang bakat vokal dan seni musik Islami (Nasyid Acapella & Akustik).',
            'deskripsi_lengkap' => 'Komunitas Nasyid memfasilitasi siswa yang hobi menyanyi dan bermusik dengan karya-karya positif yang menyejukkan hati. Rutin tampil di pentas seni sekolah, perlombaan antar-SMA, dan acara peringatan hari besar Islam.',
            'role' => [
                'Ketua Tim Nasyid',
                'Vocal Director & Arranger',
                'Koordinator Beatbox / Vocal Percussion',
                'Sie Peralatan & Sound Management',
                'Koordinator Penampilan & Lomba',
            ],
            'kegiatan_rutin' => [
                'Latihan Harmonisasi Vokal Pekanan',
                'Cover Nasyid & Perekaman Audio/Video',
                'Penampilan Acara Resmi Sekolah & FSI',
                'Partisipasi Lomba Nasyid Tingkat Sumbar',
            ],
        ],

        [
            'slug' => 'kaligrafi',
            'nama' => 'Komunitas Kaligrafi & Khat',
            'motto' => 'Keindahan Al-Qur\'an Dalam Goresan Kuas dan Pena',
            'logo' => 'images/icon/leora.svg.png',
            'warna_aksen' => '#7C3AED', // Violet
            'deskripsi_singkat' => 'Komunitas seni lukis dan penulisan kaligrafi Arab (Khat Naskhi, Diwani, Kufi).',
            'deskripsi_lengkap' => 'Komunitas Kaligrafi membina ketelitian dan keindahan seni rupa Islam. Anggota diajarkan kaidah penulisan khat klasik hingga seni lukis kontemporer untuk pameran sekolah dan kompetisi seni.',
            'role' => [
                'Ketua Komunitas Kaligrafi',
                'Instruktur Khat Naskhi & Diwani',
                'Koordinator Seni Rupa & Pameran',
                'Sie Bahan & Peralatan Lukis',
            ],
            'kegiatan_rutin' => [
                'Workshop Penulisan Khat Dasar',
                'Pembuatan Mural & Hiasan Kaligrafi',
                'Pameran Seni Kaligrafi Ramadan',
                'Penyusunan Portofolio Karya Anggota',
            ],
        ],

        [
            'slug' => 'panahan',
            'nama' => 'Komunitas Panahan & Olahraga Sunnah',
            'motto' => 'Fokus, Tangkas, Membidik Ketepatan dengan Jiwa Ksatria',
            'logo' => 'images/icon/growup.svg.png',
            'warna_aksen' => '#0284C7', // Sky Blue
            'deskripsi_singkat' => 'Pusat latihan panahan (archery) tradisional dan olahraga sunnah fisik.',
            'deskripsi_lengkap' => 'Komunitas Panahan mengasah kedisiplinan, ketenangan, dan kekuatan fisik anggota melalui olahraga sunnah panahan. Dilengkapi dengan pelatihan teknik memanah, perawatan busur, dan kompetisi internal.',
            'role' => [
                'Ketua Komunitas Panahan',
                'Pelatih Kepala (Archery Coach)',
                'Sie Logistik & Peralatan Busur',
                'Koordinator Keamanan Lapangan',
            ],
            'kegiatan_rutin' => [
                'Latihan Memanah Pekanan Lapangan SMANSA',
                'Scoring Day & Turnamen Internal',
                'Edukasi Perawatan Busur & Anak Panah',
                'Latihan Stamina & Ketangkasan',
            ],
        ],

        [
            'slug' => 'kemuslimahan',
            'nama' => 'Komunitas Kemuslimahan',
            'motto' => 'Anggun Berkarya, Tangguh Beragama, Menjadi Muslimah Sejati',
            'logo' => 'images/icon/Group 50.png',
            'warna_aksen' => '#EC4899', // Pink Emerald
            'deskripsi_singkat' => 'Wadah khusus siswi SMANSA untuk pembelajaran fiqih an-nisaa\', tata krama, dan keterampilan.',
            'deskripsi_lengkap' => 'Komunitas Kemuslimahan memberikan bimbingan khusus seputar fiqih wanita, kesehatan remaja putri, tata krama, tata boga, serta keterampilan praktis yang membekali siswi menjadi generasi muslimah berkualitas.',
            'role' => [
                'Ketua Nisaa\' FSI',
                'Koordinator Kajian Fiqih Wanita',
                'Sie Keterampilan & Tata Boga',
                'Koordinator Konseling Teman Sebaya',
            ],
            'kegiatan_rutin' => [
                'Keputrian Jumat Rutin',
                'Workshop Cooking & Craft Muslimah',
                'Kajian Spesial Kesehatan & Fiqih Nisaa\'',
                'Sharing Session & Mentoring Remaja',
            ],
        ],

        [
            'slug' => 'humas-media',
            'nama' => 'Komunitas Humas & Media Kreatif',
            'motto' => 'Kreativitas Tanpa Batas untuk Syiar Digital yang Estetik',
            'logo' => 'images/icon/tsaqib-media.svg.png',
            'warna_aksen' => '#EA580C', // Orange Flame
            'deskripsi_singkat' => 'Tim desain grafis, videografi, pengelolaan media sosial, dan branding FSI.',
            'deskripsi_lengkap' => 'Komunitas Humas & Media bertanggung jawab atas publikasi digital FSI. Menguasai software desain, editing video, live streaming kegiatan, hingga pengelolaan feed Instagram & YouTube resmi TSAQIB.',
            'role' => [
                'Ketua Tim Media & Humas',
                'Lead Graphic Designer',
                'Videographer & Video Editor',
                'Social Media Manager & Copywriter',
                'Public Relations & External Liaison',
            ],
            'kegiatan_rutin' => [
                'Pembuatan Konten Poster & Short Video',
                'Liputan & Live Streaming Event FSI',
                'Pelatihan Desain Grafis & Videografi',
                'Branding & Manajemen Medsos FSI',
            ],
        ],

        [
            'slug' => 'bahasa-arab',
            'nama' => 'Komunitas Lughah & Bahasa Arab',
            'motto' => 'Bahasa Al-Qur\'an, Jembatan Memahami Samudera Ilmu',
            'logo' => 'images/icon/Group 14.png',
            'warna_aksen' => '#0D9488', // Teal
            'deskripsi_singkat' => 'Klub percakapan (muhadatsah), tata bahasa (nahwu sharaf), dan debat Bahasa Arab.',
            'deskripsi_lengkap' => 'Komunitas Bahasa Arab membantu siswa mempelajari bahasa Al-Qur\'an secara menyenangkan melalui metode muhadatsah harian, mahfuzhat (kata mutiara), hingga persiapan kompetisi debat Bahasa Arab tingkat pelajar.',
            'role' => [
                'Ketua Klub Lughah',
                'Koordinator Muhadatsah Harian',
                'Sie Nahwu & Sharaf',
                'Koordinator Tim Debat Bahasa Arab',
            ],
            'kegiatan_rutin' => [
                'Yaumul Lughah (Hari Berbahasa Arab)',
                'Kajian Kitab Gundul & Nahwu Sharaf',
                'Debat & Pidato Bahasa Arab (Khitabah)',
                'Kuis Kosa Kata Arab Harian',
            ],
        ],

        [
            'slug' => 'hadrah',
            'nama' => 'Komunitas Hadrah & Shalawat',
            'motto' => 'Lantunan Shalawat Pembawa Ketenangan Hati dan Syafaat',
            'logo' => 'images/icon/Group 12.png',
            'warna_aksen' => '#16A34A', // Green Leaf
            'deskripsi_singkat' => 'Grup seni terbang / rebana hadrah penyemarak acara keagamaan dan shalawat.',
            'deskripsi_lengkap' => 'Komunitas Hadrah melestarikan seni musik tradisional rebana hadrah untuk melantunkan qasidah dan shalawat Nabi. Menjadi pengisi utama dalam Peringatan Hari Besar Islam (PHBI) dan acara resmi SMAN 1 Bukittinggi.',
            'role' => [
                'Ketua Tim Hadrah',
                'Koordinator Pukulan Rebana (Keprak/Bass)',
                'Vokalis Utama Shalawat',
                'Sie Logistik & Alat Musik',
            ],
            'kegiatan_rutin' => [
                'Latihan Pukulan Hadrah & Variasi',
                'Rutinan Shalawat Majlis Ta\'lim Pekanan',
                'Pengisi Acara Maulid & Isra\' Mi\'raj',
                'Kolaborasi Musik Islami',
            ],
        ],

        [
            'slug' => 'literasi-islam',
            'nama' => 'Komunitas Literasi & Buletin FSI',
            'motto' => 'Pena Pembawa Perubahan, Menuang Pemikiran Dalam Tulisan',
            'logo' => 'images/icon/tsaqib-press.svg.png',
            'warna_aksen' => '#475569', // Slate Dark
            'deskripsi_singkat' => 'Pusat penulisan artikel, esai keislaman, cerpen, dan majalah berkala TSAQIB.',
            'deskripsi_lengkap' => 'Komunitas Literasi membina kemampuan jurnalistik dan penulisan ilmiah keislaman. Menghasilkan karya tulisan buletin cetak, artikel blog TSAQIB, serta resensi buku-buku perpustakaan FSI.',
            'role' => [
                'Pemred Buletin TSAQIB',
                'Editor Artikel & Cerpen',
                'Jurnalis & Reporter Liputan',
                'Sie Layout & Cetak Majalah',
            ],
            'kegiatan_rutin' => [
                'Penerbitan Buletin Kebangkitan FSI',
                'Klinik Menulis Artikel & Esai',
                'Resensi Buku Digital Perpustakaan',
                'Diskusi Bedah Buku Keislaman',
            ],
        ],

        [
            'slug' => 'sosial-peduli',
            'nama' => 'Komunitas FSI Peduli & Baksos',
            'motto' => 'Aksi Nyata Kepedulian, Tangan Di Atas Lebih Baik Dari Tangan Di Bawah',
            'logo' => 'images/icon/Group 49.png',
            'warna_aksen' => '#DC2626', // Crimson Red
            'deskripsi_singkat' => 'Badan aksi sosial, penggalangan dana kemanusiaan, dan bakti sosial masyarakat.',
            'deskripsi_lengkap' => 'Komunitas FSI Peduli bergerak di bidang aksi sosial cepat tanggap, penyaluran sembako warga kurang mampu, bantuan bencana alam, serta bakti sosial ramadan di Bukittinggi dan sekitarnya.',
            'role' => [
                'Ketua FSI Peduli',
                'Koordinator Penggalangan Dana & Posko',
                'Sie Logistik & Penyaluran Bantuan',
                'Koordinator Relawan Siswa',
            ],
            'kegiatan_rutin' => [
                'Jumat Berkah & Berbagi Nasi',
                'Penggalangan Dana Bencana Alam',
                'Bakti Sosial & Santunan Anak Yatim',
                'Donor Darah & Posko Kemanusiaan',
            ],
        ],

        [
            'slug' => 'entrepreneur',
            'nama' => 'Komunitas Islamic Entrepreneur',
            'motto' => 'Kewirausahaan Syariah, Kemandirian Ekonomi Generasi Muda',
            'logo' => 'images/icon/Asset 6@4x 1.png',
            'warna_aksen' => '#B45309', // Amber Warm
            'deskripsi_singkat' => 'Pusat wirausaha muda FSI (Kantin Kejujuran, Merchandise TSAQIB, & Bazar).',
            'deskripsi_lengkap' => 'Komunitas Islamic Entrepreneur melatih jiwa wirausaha berlandaskan prinsip syariah. Mengelola kas mandiri organisasi melalui penjualan merchandise resmi FSI, snack halal, serta bazar kewirausahaan sekolah.',
            'role' => [
                'Ketua Bidang Ekonomi FSI',
                'Manager Merchandise & Stok',
                'Sie Keuangan & Kas Wirausaha',
                'Koordinator Stand Bazar & Event',
            ],
            'kegiatan_rutin' => [
                'Pengelolaan Merchandise & Kaos FSI',
                'Bazar Makanan Halal Event Sekolah',
                'Pelatihan Bisnis Digital & Akad Syariah',
                'Manajemen Kantin Kejujuran',
            ],
        ],

        [
            'slug' => 'kajian-ilmiah',
            'nama' => 'Komunitas Science & Islam',
            'motto' => 'Menguak Rahasia Alam Semesta Melalui Teropong Al-Qur\'an dan Sains',
            'logo' => 'images/icon/growup.svg.png',
            'warna_aksen' => '#4338CA', // Indigo Deep
            'deskripsi_singkat' => 'Klub diskusi korelasi ayat-ayat Al-Qur\'an dengan sains modern & Astronomi Islam (Falak).',
            'deskripsi_lengkap' => 'Komunitas Science & Islam mengkaji fenomena fisika, biologi, astronomi, dan kedokteran yang dijelaskan dalam Al-Qur\'an. Anggota dilatih menganalisis ayat-ayat kauniyah dan praktik ilmu falak (penentuan arah kiblat & hilal).',
            'role' => [
                'Ketua Klub Sains Islam',
                'Koordinator Riset Ayat Kauniyah',
                'Sie Astronomi & Ilmu Falak',
                'Koordinator Diskusi & Presentation',
            ],
            'kegiatan_rutin' => [
                'Kajian Ayat-Ayat Kauniyah & Sains',
                'Pengamatan Hilal & Arah Kiblat',
                'Penyusunan Artikel Sains Keislaman',
                'Seminar Integrasi Ilmu & Agama',
            ],
        ],

    ],

];
