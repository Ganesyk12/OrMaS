<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\UserAccess\Models\UserAccessModel;
use Tests\TestCase;

class ModuleAccessMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_is_redirected_to_login_for_html_requests(): void
    {
        $this->get('/organizations')->assertRedirect(route('login'));
    }

    /** @test */
    public function guest_receives_unauthorized_for_json_requests(): void
    {
        $this->getJson('/organizations')->assertUnauthorized();
    }

    /** @test */
    public function dashboard_requires_authentication(): void
    {
        $this->get('/dashboard')->assertRedirect(route('login'));
    }

    /** @test */
    public function user_without_module_access_receives_forbidden(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->getJson('/organizations')
            ->assertForbidden();
    }

    /** @test */
    public function view_only_user_can_list_but_cannot_create(): void
    {
        $user = User::factory()->create();

        UserAccessModel::create([
            'user_id' => $user->id,
            'module' => 'organizations',
            'allow_view' => true,
            'allow_create' => false,
            'allow_edit' => false,
            'allow_delete' => false,
        ]);

        $this->actingAs($user)
            ->getJson('/organizations')
            ->assertOk();

        $this->actingAs($user)
            ->getJson('/organizations/create')
            ->assertForbidden();
    }

    /** @test */
    public function module_access_is_checked_per_module(): void
    {
        $user = User::factory()->create();

        UserAccessModel::create([
            'user_id' => $user->id,
            'module' => 'members',
            'allow_view' => true,
            'allow_create' => true,
            'allow_edit' => true,
            'allow_delete' => true,
        ]);

        $this->actingAs($user)
            ->getJson('/members')
            ->assertOk();

        $this->actingAs($user)
            ->getJson('/organizations')
            ->assertForbidden();
    }

    /** @test */
    public function sidebar_only_shows_modules_the_user_can_view(): void
    {
        $user = User::factory()->create();

        UserAccessModel::create([
            'user_id' => $user->id,
            'module' => 'members',
            'allow_view' => true,
            'allow_create' => false,
            'allow_edit' => false,
            'allow_delete' => false,
        ]);

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertOk()
            ->assertSee('<span class="pc-mtext">Anggota</span>', false)
            ->assertDontSee('<span class="pc-mtext">Data Organisasi</span>', false);
    }

    /** @test */
    public function delete_action_requires_delete_permission(): void
    {
        $user = User::factory()->create();

        UserAccessModel::create([
            'user_id' => $user->id,
            'module' => 'organizations',
            'allow_view' => true,
            'allow_create' => true,
            'allow_edit' => true,
            'allow_delete' => false,
        ]);

        $this->actingAs($user)
            ->deleteJson('/organizations/1')
            ->assertForbidden();
    }
}
