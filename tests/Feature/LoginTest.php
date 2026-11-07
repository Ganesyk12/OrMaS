<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function login_page_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Masuk ke akun Anda');
    }

    /** @test */
    public function user_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('dashboard.index'));
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function user_cannot_login_with_invalid_credentials(): void
    {
        $user = User::factory()->create();

        $response = $this->from('/login')->post('/login', [
            'email' => $user->email,
            'password' => 'password-yang-salah',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /** @test */
    public function user_cannot_login_with_unknown_email(): void
    {
        $response = $this->from('/login')->post('/login', [
            'email' => 'tidak-terdaftar@example.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors(['email' => 'Email atau password salah.']);
        $this->assertGuest();
    }

    /** @test */
    public function login_page_shows_session_expired_message(): void
    {
        $response = $this->get('/login?expired=1');

        $response->assertOk();
        $response->assertSee('Sesi halaman kedaluwarsa');
    }
}
