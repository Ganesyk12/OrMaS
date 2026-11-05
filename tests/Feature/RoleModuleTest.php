<?php

use Database\Factories\RoleFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Role\Models\RoleModel;
use Tests\Concerns\AuthenticatesModuleAccess;
use Tests\TestCase;

class RoleModuleTest extends TestCase
{
    use AuthenticatesModuleAccess, RefreshDatabase;

    /** @test */
    public function role_can_be_listed(): void
    {
        RoleFactory::new()->count(3)->create();

        $response = $this->getJson('/roles');

        $response->assertStatus(200);
        $response->assertJsonCount(3, 'data');
    }

    /** @test */
    public function role_can_be_created(): void
    {
        $payload = RoleFactory::new()->make()->toArray();

        $response = $this->postJson('/roles', $payload);

        $response->assertStatus(201);
        $this->assertDatabaseHas('roles', ['code' => $payload['code']]);
    }

    /** @test */
    public function role_can_be_shown(): void
    {
        $role = RoleFactory::new()->create();

        $response = $this->getJson('/roles/'.$role->id);

        $response->assertStatus(200);
        $response->assertJsonFragment(['id' => $role->id]);
    }

    /** @test */
    public function role_can_be_updated(): void
    {
        $role = RoleFactory::new()->create();

        $response = $this->putJson('/roles/'.$role->id, [
            'name' => 'Jabatan Diperbarui',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('roles', [
            'id' => $role->id,
            'name' => 'Jabatan Diperbarui',
        ]);
    }

    /** @test */
    public function role_can_be_deleted(): void
    {
        $role = RoleFactory::new()->create();

        $response = $this->deleteJson('/roles/'.$role->id);

        $response->assertStatus(200);
        $this->assertSoftDeleted('roles', ['id' => $role->id]);
    }
}
