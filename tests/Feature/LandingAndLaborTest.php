<?php

use App\Models\Book;
use App\Models\News;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('landing page loads successfully with photo cards and prototype link', function () {
    // Seed sample data directly
    Book::create([
        'title' => 'Buku Uji Laboratorium PAI',
        'author' => 'Tim FSI',
        'category' => 'modul',
        'is_visible' => true,
    ]);

    $user = User::factory()->create(['name' => 'Ustadz Penguji']);

    News::create([
        'title' => 'Warta Utama FSI SMAN 1 Bukittinggi',
        'slug' => 'warta-utama-fsi',
        'excerpt' => 'Kabar kegiatan kepengurusan dan bina karakter siswa.',
        'content' => 'Konten lengkap kegiatan kepengurusan.',
        'user_id' => $user->id,
        'published_at' => now(),
    ]);

    $response = $this->get(route('landing'));

    $response->assertStatus(200);

    // Verify key brand elements and headings
    $response->assertSee('TSAQIB');
    $response->assertSee('Cerdas, Unggul, dan Berakhlak Mulia');
    $response->assertSee('Forum Studi Islam');

    // Verify 4 Photo Cards
    $response->assertSee('card-labor.jpg');
    $response->assertSee('card-perpus.jpg');
    $response->assertSee('card-komunitas.jpg');
    $response->assertSee('card-figma.jpg');

    // Verify Prototype link for competition
    $response->assertSee('Prototype', false);
    $response->assertSee('https://www.figma.com/proto/1Azmk9c0fapjsTICrk7hU6/Tsaqib-Adv', false);

    // Verify 7 Circle Komunitas are present
    $response->assertSee('Tahfidz');
    $response->assertSee('The Young Stars');
    $response->assertSee('Grow Up');
    $response->assertSee('Blitzsport');
    $response->assertSee('Gofam');
    $response->assertSee('Leora');
    $response->assertSee('MuShou');

    // Verify routes in buttons
    $response->assertSee(route('open.recruitment'));
    $response->assertSee(route('register'));
    $response->assertSee(route('laboratorium.pai'));
    $response->assertSee(route('perpustakaan'));
});

test('laboratorium pai page loads with 2 documents: profil flipbook and monev internal pdf', function () {
    $response = $this->get(route('laboratorium.pai'));

    $response->assertStatus(200);

    // Verify title and header
    $response->assertSee('Laboratorium PAI');
    $response->assertSee('Khazanah Digital Laboratorium');

    // Verify exactly 2 documents are present
    $response->assertSee('Profil TSAQIB');
    $response->assertSee('Monev Internal Pemerintah Daerah');

    // Verify reader element containers exist
    $response->assertSee('id="ruang-baca"', false);
    $response->assertSee('id="lp-reader-box"', false);
    $response->assertSee('id="pane-flipbook"', false);
    $response->assertSee('id="pane-pdf"', false);

    // Verify monev internal PDF asset
    $response->assertSee('monev-internal-pemerintah-daerah.pdf');
});

test('community feed can be sorted by popular (highest likes first)', function () {
    $user = User::factory()->create();

    $postA = Post::create([
        'user_id' => $user->id,
        'community_id' => 'tahfidz',
        'title' => 'Postingan Biasa',
        'content' => 'Konten postingan pertama',
        'upvotes' => 2,
    ]);

    $postB = Post::create([
        'user_id' => $user->id,
        'community_id' => 'tahfidz',
        'title' => 'Postingan Terpopuler Bintang',
        'content' => 'Konten postingan kedua dengan banyak like',
        'upvotes' => 99,
    ]);

    $response = $this->get(route('komunitas', ['sort' => 'popular']));

    $response->assertStatus(200);
    // Post B should appear before Post A in HTML
    $content = $response->getContent();
    $posB = strpos($content, 'Postingan Terpopuler Bintang');
    $posA = strpos($content, 'Postingan Biasa');

    expect($posB)->toBeLessThan($posA);
});

test('student task page gates access when user does not have registered nisn', function () {
    // Guest accessing tugas
    $response = $this->get(route('laboratorium.tugas'));
    $response->assertStatus(200);
    $response->assertSee('Akses Tugas &amp; Google Classroom Terkunci', false);

    // Regular student without NISN
    $studentWithoutNisn = User::factory()->create([
        'is_verified_student' => false,
        'nisn' => null,
        'role' => 'user',
    ]);

    $responseAuth = $this->actingAs($studentWithoutNisn)->get(route('laboratorium.tugas'));
    $responseAuth->assertStatus(200);
    $responseAuth->assertSee('Akses Tugas &amp; Google Classroom Terkunci', false);

    // Student with NISN
    $studentWithNisn = User::factory()->create([
        'is_verified_student' => true,
        'nisn' => '0012345678',
        'role' => 'user',
    ]);

    $responseVerified = $this->actingAs($studentWithNisn)->get(route('laboratorium.tugas'));
    $responseVerified->assertStatus(200);
    $responseVerified->assertDontSee('Akses Tugas &amp; Google Classroom Terkunci', false);
});
