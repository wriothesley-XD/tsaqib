<?php

use App\Models\GuruProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

test('admin can view gurus tab on admin panel', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $guru = GuruProfile::create([
        'nama' => 'Ustadz Abdullah, S.Pd.I.',
        'nip' => '198501012010011005',
        'mapel_pengampu' => 'Pendidikan Agama Islam',
        'kelas_diampu' => ['X', 'XI'],
        'deskripsi' => 'Pengampu materi Aqidah dan Fiqih praktis.',
        'facebook_url' => 'https://facebook.com/ustadz.abdullah',
        'instagram_url' => 'https://instagram.com/ustadz.abdullah',
        'email' => 'abdullah@fsi.sch.id',
        'wa_number' => '081234567890',
    ]);

    $response = $this->actingAs($admin)->get(route('admin.index', ['tab' => 'gurus']));

    $response->assertStatus(200);
    $response->assertSee('Guru Pengampu');
    $response->assertSee('Ustadz Abdullah, S.Pd.I.');
    $response->assertSee('Pengampu materi Aqidah dan Fiqih praktis.');
});

test('admin can store a new guru profile with photo and social media links', function () {
    Storage::fake('public');
    $admin = User::factory()->create(['role' => 'admin']);

    $file = UploadedFile::fake()->createWithContent('guru-profile.jpg', str_repeat("\xFF\xD8\xFF", 100));

    $response = $this->actingAs($admin)->post(route('admin.gurus.store'), [
        'nama' => 'Ustadzah Fatimah, M.Pd.',
        'nip' => '199002022015022003',
        'mapel_pengampu' => 'Pendidikan Agama Islam & Budi Pekerti',
        'kelas_diampu' => ['X', 'XII'],
        'deskripsi' => 'Membina tahsin Al-Qur\'an dan akhlak mulia.',
        'facebook_url' => 'https://facebook.com/ustadzah.fatimah',
        'instagram_url' => '@fatimah_pai',
        'email' => 'fatimah@fsi.sch.id',
        'wa_number' => '089876543210',
        'foto' => $file,
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('guru_profiles', [
        'nama' => 'Ustadzah Fatimah, M.Pd.',
        'nip' => '199002022015022003',
        'email' => 'fatimah@fsi.sch.id',
        'deskripsi' => 'Membina tahsin Al-Qur\'an dan akhlak mulia.',
        'facebook_url' => 'https://facebook.com/ustadzah.fatimah',
        'instagram_url' => '@fatimah_pai',
        'wa_number' => '089876543210',
    ]);

    $createdGuru = GuruProfile::where('nama', 'Ustadzah Fatimah, M.Pd.')->first();
    expect($createdGuru)->not->toBeNull();
    expect($createdGuru->foto_path)->not->toBeNull();
    Storage::disk('public')->assertExists($createdGuru->foto_path);
});

test('admin can update an existing guru profile', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $guru = GuruProfile::create([
        'nama' => 'Ustadz Lama',
        'mapel_pengampu' => 'Pendidikan Agama Islam',
        'deskripsi' => 'Deskripsi lama.',
    ]);

    $response = $this->actingAs($admin)->put(route('admin.gurus.update', $guru->id), [
        'nama' => 'Ustadz Baru, S.Ag.',
        'nip' => '198203032008011002',
        'mapel_pengampu' => 'Pendidikan Agama Islam',
        'kelas_diampu' => ['XI'],
        'deskripsi' => 'Deskripsi baru yang telah diperbarui.',
        'facebook_url' => 'https://facebook.com/ustadz.baru',
        'instagram_url' => 'https://instagram.com/ustadz.baru',
        'email' => 'baru@fsi.sch.id',
        'wa_number' => '081122334455',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('success');

    $guru->refresh();
    expect($guru->nama)->toBe('Ustadz Baru, S.Ag.');
    expect($guru->nip)->toBe('198203032008011002');
    expect($guru->deskripsi)->toBe('Deskripsi baru yang telah diperbarui.');
    expect($guru->email)->toBe('baru@fsi.sch.id');
});

test('admin can delete a guru profile', function () {
    Storage::fake('public');
    $admin = User::factory()->create(['role' => 'admin']);

    $photoPath = 'guru/test-delete.jpg';
    Storage::disk('public')->put($photoPath, 'fake-image-content');

    $guru = GuruProfile::create([
        'nama' => 'Ustadz Hapus',
        'foto_path' => $photoPath,
    ]);

    $response = $this->actingAs($admin)->delete(route('admin.gurus.destroy', $guru->id));

    $response->assertRedirect();
    $response->assertSessionHas('success');

    $this->assertDatabaseMissing('guru_profiles', ['id' => $guru->id]);
    Storage::disk('public')->assertMissing($photoPath);
});

test('public labor profil page displays Uiverse-styled guru cards with facebook and instagram', function () {
    $guru = GuruProfile::create([
        'nama' => 'Ustadz Dr. H. Syahrul, M.A.',
        'nip' => '197505052000031001',
        'mapel_pengampu' => 'Pendidikan Agama Islam',
        'kelas_diampu' => ['X', 'XI', 'XII'],
        'deskripsi' => 'Pakar Fiqih Perbandingan dan Pembimbing Ruhani Sekolah.',
        'facebook_url' => 'https://facebook.com/ustadz.syahrul',
        'instagram_url' => 'https://instagram.com/syahrul_pai',
        'email' => 'syahrul@fsi.sch.id',
        'wa_number' => '081399887766',
    ]);

    $response = $this->get(route('laboratorium.profil'));

    $response->assertStatus(200);
    $response->assertSee('guru-card');
    $response->assertSee('Ustadz Dr. H. Syahrul, M.A.');
    $response->assertSee('Pakar Fiqih Perbandingan dan Pembimbing Ruhani Sekolah.');
    $response->assertSee('mailto:syahrul@fsi.sch.id', false);
    $response->assertSee('https://facebook.com/ustadz.syahrul', false);
    $response->assertSee('https://instagram.com/syahrul_pai', false);
    $response->assertSee('https://wa.me/081399887766', false);
});
