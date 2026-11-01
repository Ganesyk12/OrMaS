<?php

use App\Models\User;
use Database\Factories\UserAccessFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\UserAccess\Models\UserAccessModel;
use Tests\TestCase;

class UserAccessModuleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_access_can_be_listed(): void
    {
        $user = User::factory()->create();
        UserAccessFactory::new()->count(3)->create(['user_id' => $user->id]);

        $response = $this->getJson('/user-accesses');

        $response->assertStatus(200);
        $response->assertJsonCount(3, 'data');
    }

    /** @test */
    public function user_access_can_be_created(): void
    {
        $user = User::factory()->create();

        $response = $this->postJson('/user-accesses', [
            'user_id' => $user->id,
            'module' => 'finances',
            'permission' => 'view',
            'is_allowed' => true,
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('user_accesses', [
            'user_id' => $user->id,
            'module' => 'finances',
            'permission' => 'view',
        ]);
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
            'is_allowed' => false,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('user_accesses', [
            'id' => $access->id,
            'is_allowed' => false,
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
