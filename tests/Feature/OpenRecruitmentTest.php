<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OpenRecruitmentTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Halaman Open Recruitment dapat diakses publik tanpa login.
     */
    public function test_open_recruitment_page_is_publicly_accessible(): void
    {
        $response = $this->get('/open-recruitment');

        $response->assertStatus(200);
        $response->assertSee('Open Recruitment');
    }

    /**
     * Halaman Hub Masjid dapat diakses publik tanpa login.
     */
    public function test_hub_page_is_publicly_accessible(): void
    {
        $response = $this->get('/hub');

        $response->assertStatus(200);
        $response->assertSee('Laboratorium PAI');
        $response->assertSee('Open Recruitment');
    }

    /**
     * Halaman Laboratorium PAI dapat diakses publik tanpa login.
     */
    public function test_laboratorium_pai_is_publicly_accessible(): void
    {
        $response = $this->get('/laboratorium-pai');

        $response->assertStatus(200);
    }

    /**
     * Form pendaftaran Open Recruitment dapat disubmit dan menyimpan data dengan kolom yang benar.
     */
    public function test_open_recruitment_can_be_submitted_successfully(): void
    {
        $response = $this->post('/open-recruitment', [
            'nama_lengkap' => 'Ahmad Rabbani',
            'nama_panggilan' => 'Ahmad',
            'kelas' => 'X.1',
            'instagram_username' => 'ahmad_rabbani',
            'alasan_bergabung' => 'Ingin memperdalam ilmu keislaman dan aktif di komunitas TSAQIB SMAN 1 Bukittinggi.',
        ]);

        $response->assertRedirect(route('open.recruitment.thank-you'));

        $this->assertDatabaseHas('registrations', [
            'full_name' => 'Ahmad Rabbani',
            'nickname' => 'Ahmad',
            'class' => 'X.1',
            'username_ig' => 'ahmad_rabbani',
            'reason' => 'Ingin memperdalam ilmu keislaman dan aktif di komunitas TSAQIB SMAN 1 Bukittinggi.',
        ]);
    }
}
