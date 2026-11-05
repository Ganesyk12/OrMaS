<?php

use Database\Factories\OrganizationFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Organization\Models\OrganizationModel;
use Tests\Concerns\AuthenticatesModuleAccess;
use Tests\TestCase;

class OrganizationModuleTest extends TestCase
{
    use AuthenticatesModuleAccess, RefreshDatabase;

    /** @test */
    public function organization_can_be_listed(): void
    {
        OrganizationFactory::new()->count(3)->create();

        $response = $this->getJson('/organizations');

        $response->assertStatus(200);
        $response->assertJsonCount(3, 'data');
    }

    /** @test */
    public function organization_can_be_created(): void
    {
        $payload = OrganizationFactory::new()->make()->toArray();

        $response = $this->postJson('/organizations', $payload);

        $response->assertStatus(201);
        $this->assertDatabaseHas('organizations', ['code' => $payload['code']]);
    }

    /** @test */
    public function organization_can_be_shown(): void
    {
        $organization = OrganizationFactory::new()->create();

        $response = $this->getJson('/organizations/'.$organization->id);

        $response->assertStatus(200);
        $response->assertJsonFragment(['id' => $organization->id]);
    }

    /** @test */
    public function organization_can_be_updated(): void
    {
        $organization = OrganizationFactory::new()->create();

        $response = $this->putJson('/organizations/'.$organization->id, [
            'name' => 'Organisasi Diperbarui',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('organizations', [
            'id' => $organization->id,
            'name' => 'Organisasi Diperbarui',
        ]);
    }

    /** @test */
    public function organization_can_be_deleted(): void
    {
        $organization = OrganizationFactory::new()->create();

        $response = $this->deleteJson('/organizations/'.$organization->id);

        $response->assertStatus(200);
        $this->assertSoftDeleted('organizations', ['id' => $organization->id]);
    }
}
