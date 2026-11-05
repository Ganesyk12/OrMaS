<?php

use App\Models\User;
use Database\Factories\UserAccessFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\UserAccess\Models\ModuleModel;
use Modules\UserAccess\Models\UserAccessModel;
use Tests\Concerns\AuthenticatesModuleAccess;
use Tests\TestCase;

class UserAccessModuleTest extends TestCase
{
    use AuthenticatesModuleAccess, RefreshDatabase;

    /** @test */
    public function user_access_can_be_listed(): void
    {
        $user = User::factory()->create();

        foreach (['organizations', 'members', 'finances'] as $module) {
            UserAccessFactory::new()->create([
                'user_id' => $user->id,
                'module' => $module,
            ]);
        }

        $response = $this->getJson('/user-accesses?user_id='.$user->id);

        $response->assertStatus(200);
        $response->assertJsonCount(3, 'data');
    }

    /** @test */
    public function user_access_index_uses_bootstrap_pagination(): void
    {
        $users = User::factory()->count(2)->create();
        $modules = array_slice(ModuleModel::activeNames(), 0, 8);

        foreach ($users as $user) {
            foreach ($modules as $module) {
                UserAccessFactory::new()->create([
                    'user_id' => $user->id,
                    'module' => $module,
                ]);
            }
        }

        $response = $this->get('/user-accesses');

        $response->assertOk();
        $response->assertSee('<ul class="pagination">', false);
        $response->assertSee('page-item', false);
    }

    /** @test */
    public function user_access_can_be_created(): void
    {
        $user = User::factory()->create();

        $response = $this->postJson('/user-accesses', [
            'user_id' => $user->id,
            'module' => 'finances',
            'allow_view' => true,
            'allow_create' => true,
            'allow_edit' => false,
            'allow_delete' => false,
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('user_accesses', [
            'user_id' => $user->id,
            'module' => 'finances',
            'allow_view' => true,
            'allow_create' => true,
        ]);
    }

    /** @test */
    public function user_access_forms_show_permission_matrix_for_all_modules(): void
    {
        $createResponse = $this->get('/user-accesses/create');
        $createResponse->assertOk();
        $createResponse->assertSee('name="access[organizations][view]"', false);
        $createResponse->assertSee('name="access[letter-categories][delete]"', false);

        $user = User::factory()->create();
        $access = UserAccessFactory::new()->create([
            'user_id' => $user->id,
            'module' => 'organizations',
        ]);

        $editResponse = $this->get('/user-accesses/'.$access->id.'/edit');
        $editResponse->assertOk();
        $editResponse->assertSee('name="access[organizations][view]"', false);
        $editResponse->assertSee('name="access[finances][view]"', false);
    }

    /** @test */
    public function user_access_forms_follow_active_modules_from_database(): void
    {
        ModuleModel::create([
            'name' => 'reports',
            'label' => 'Laporan Custom',
            'sort_order' => 99,
            'is_active' => true,
        ]);

        ModuleModel::where('name', 'finances')->update(['is_active' => false]);

        $response = $this->get('/user-accesses/create');

        $response->assertOk();
        $response->assertSee('name="access[reports][view]"', false);
        $response->assertSee('Laporan Custom');
        $response->assertDontSee('name="access[finances][view]"', false);
    }

    /** @test */
    public function user_access_can_be_stored_in_bulk_for_multiple_modules(): void
    {
        $user = User::factory()->create();

        $response = $this->postJson('/user-accesses', [
            'access_mode' => 1,
            'user_id' => $user->id,
            'access' => [
                'organizations' => ['view' => true, 'create' => true],
                'finances' => ['view' => true, 'delete' => true],
            ],
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('user_accesses', [
            'user_id' => $user->id,
            'module' => 'organizations',
            'allow_view' => true,
            'allow_create' => true,
            'allow_edit' => false,
            'allow_delete' => false,
        ]);
        $this->assertDatabaseHas('user_accesses', [
            'user_id' => $user->id,
            'module' => 'finances',
            'allow_view' => true,
            'allow_create' => false,
            'allow_edit' => false,
            'allow_delete' => true,
        ]);
        $this->assertSame(2, UserAccessModel::where('user_id', $user->id)->count());
    }

    /** @test */
    public function bulk_store_does_not_remove_existing_access_for_unchecked_modules(): void
    {
        $user = User::factory()->create();
        $existingAccess = UserAccessFactory::new()->create([
            'user_id' => $user->id,
            'module' => 'finances',
        ]);

        $response = $this->postJson('/user-accesses', [
            'access_mode' => 1,
            'user_id' => $user->id,
            'access' => [
                'organizations' => ['view' => true],
            ],
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('user_accesses', [
            'id' => $existingAccess->id,
            'deleted_at' => null,
        ]);
    }

    /** @test */
    public function user_access_can_be_updated_in_bulk_and_unchecked_modules_removed(): void
    {
        $user = User::factory()->create();
        $organizationAccess = UserAccessFactory::new()->create([
            'user_id' => $user->id,
            'module' => 'organizations',
        ]);
        $financeAccess = UserAccessFactory::new()->create([
            'user_id' => $user->id,
            'module' => 'finances',
        ]);

        $response = $this->putJson('/user-accesses/'.$organizationAccess->id, [
            'access_mode' => 1,
            'access' => [
                'organizations' => ['view' => true, 'edit' => true],
            ],
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('user_accesses', [
            'id' => $organizationAccess->id,
            'allow_view' => true,
            'allow_create' => false,
            'allow_edit' => true,
            'allow_delete' => false,
        ]);
        $this->assertSoftDeleted('user_accesses', ['id' => $financeAccess->id]);
    }

    /** @test */
    public function user_access_can_be_recreated_after_soft_delete(): void
    {
        $user = User::factory()->create();
        $access = UserAccessFactory::new()->create([
            'user_id' => $user->id,
            'module' => 'finances',
        ]);

        $access->delete();

        $response = $this->postJson('/user-accesses', [
            'user_id' => $user->id,
            'module' => 'finances',
            'allow_view' => true,
            'allow_create' => true,
            'allow_edit' => false,
            'allow_delete' => false,
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('user_accesses', [
            'id' => $access->id,
            'user_id' => $user->id,
            'module' => 'finances',
            'allow_view' => true,
            'allow_create' => true,
            'deleted_at' => null,
        ]);
        $this->assertSame(1, UserAccessModel::withTrashed()
            ->where('user_id', $user->id)
            ->where('module', 'finances')
            ->count());
    }

    /** @test */
    public function user_access_can_be_shown(): void
    {
        $access = UserAccessFactory::new()->create();

        $response = $this->getJson('/user-accesses/'.$access->id);

        $response->assertStatus(200);
        $response->assertJsonFragment(['id' => $access->id]);
    }

    /** @test */
    public function user_access_can_be_updated(): void
    {
        $access = UserAccessFactory::new()->create();

        $response = $this->putJson('/user-accesses/'.$access->id, [
            'allow_view' => false,
            'allow_delete' => true,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('user_accesses', [
            'id' => $access->id,
            'allow_view' => false,
            'allow_delete' => true,
        ]);
    }

    /** @test */
    public function user_access_can_be_deleted(): void
    {
        $access = UserAccessFactory::new()->create();

        $response = $this->deleteJson('/user-accesses/'.$access->id);

        $response->assertStatus(200);
        $this->assertSoftDeleted('user_accesses', ['id' => $access->id]);
    }
}
