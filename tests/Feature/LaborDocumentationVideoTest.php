<?php

use App\Models\ActivityDocumentation;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('driveFileId extracts FILE_ID from semua bentuk link Google Drive', function () {
    $cases = [
        'https://drive.google.com/file/d/1AbC_deF-1234567890/view?usp=sharing' => '1AbC_deF-1234567890',
        'https://drive.google.com/open?id=1AbC_deF-1234567890' => '1AbC_deF-1234567890',
        'https://drive.google.com/uc?id=1AbC_deF-1234567890&export=view' => '1AbC_deF-1234567890',
        '1AbC_deF-1234567890XYZ' => '1AbC_deF-1234567890XYZ', // ID mentah
        'documentations/video.mp4' => null, // video lokal, bukan Drive
        null => null,
    ];

    foreach ($cases as $path => $expected) {
        $doc = new ActivityDocumentation(['video_path' => $path]);
        expect($doc->driveFileId())->toBe($expected);
    }
});

test('halaman laboratorium-pai menampilkan section dokumentasi kunjungan dengan video card', function () {
    ActivityDocumentation::create([
        'title' => 'Kunjungan Dinas Pendidikan',
        'slug' => 'kunjungan-dinas-pendidikan',
        'category' => 'Kunjungan & Studi Tiru',
        'video_path' => 'https://drive.google.com/file/d/1AbC_deF-1234567890/view',
        'event_date' => '2026-09-01',
    ]);

    $response = $this->get(route('laboratorium.pai'));

    $response->assertStatus(200);
    $response->assertSee('Dokumentasi Kunjungan &amp; Studi Tiru', false);
    $response->assertSee('drive.google.com/file/d/1AbC_deF-1234567890/preview', false);
    $response->assertSee(route('info', ['tab' => 'dokumentasi']), false);
});

test('halaman info tab dokumentasi menampilkan badge play untuk video dan filter kategori', function () {
    ActivityDocumentation::create([
        'title' => 'Kegiatan Praktikum Siswa',
        'slug' => 'kegiatan-praktikum-siswa',
        'category' => 'Kegiatan Siswa',
        'video_path' => 'https://drive.google.com/file/d/1AbC_deF-1234567890/view',
    ]);

    $response = $this->get(route('info', ['tab' => 'dokumentasi']));

    $response->assertStatus(200);
    $response->assertSee('fa-solid fa-play', false);
    $response->assertSee('data-doc-filter', false);
    $response->assertSee('Kegiatan Siswa', false);
});
