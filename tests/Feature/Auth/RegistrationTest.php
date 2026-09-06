<?php

namespace Tests\Feature\Auth;

use App\Models\NisnWhitelist;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('beranda', absolute: false));
    }

    public function test_new_user_with_whitelisted_nisn_is_auto_verified(): void
    {
        NisnWhitelist::create([
            'nisn' => '0081234567',
            'nis' => '22455',
            'nama_siswa' => 'Ahmad Fauzi',
        ]);

        $response = $this->post('/register', [
            'name' => 'Ahmad Fauzi',
            'email' => 'fauzi@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'nisn' => '0081234567',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('beranda', absolute: false));

        $user = User::where('email', 'fauzi@example.com')->first();
        $this->assertNotNull($user);
        $this->assertTrue((bool) $user->is_verified_student);
    }
}
