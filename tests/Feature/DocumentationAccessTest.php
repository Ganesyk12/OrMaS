<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DocumentationAccessTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_is_redirected_to_login(): void
    {
        $response = $this->get('/docs');

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function superadmin_can_access_documentation(): void
    {
        $superadmin = User::factory()->create(['is_superadmin' => true]);

        $response = $this->actingAs($superadmin)->get('/docs');

        $response->assertOk();
        $this->assertStringContainsString('text/html', $response->headers->get('content-type'));
    }

    /** @test */
    public function regular_admin_cannot_access_documentation(): void
    {
        $admin = User::factory()->create(['is_superadmin' => false]);

        $response = $this->actingAs($admin)->get('/docs');

        $response->assertForbidden();
    }
}
