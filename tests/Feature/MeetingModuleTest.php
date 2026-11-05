<?php

use Database\Factories\MeetingFactory;
use Database\Factories\OrganizationFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Meeting\Models\MeetingModel;
use Tests\Concerns\AuthenticatesModuleAccess;
use Tests\TestCase;

class MeetingModuleTest extends TestCase
{
    use AuthenticatesModuleAccess, RefreshDatabase;

    /** @test */
    public function meeting_can_be_listed(): void
    {
        $org = OrganizationFactory::new()->create();
        MeetingFactory::new()->count(3)->create(['organization_id' => $org->id]);

        $response = $this->getJson('/meetings');

        $response->assertStatus(200);
        $response->assertJsonCount(3, 'data');
    }

    /** @test */
    public function meeting_can_be_created(): void
    {
        $org = OrganizationFactory::new()->create();

        $response = $this->postJson('/meetings', [
            'organization_id' => $org->id,
            'title' => 'Rapat Bulanan',
            'meeting_date' => '2026-12-01 09:00:00',
            'status' => 'scheduled',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('meetings', ['title' => 'Rapat Bulanan']);
    }

    /** @test */
    public function meeting_can_be_shown(): void
    {
        $meeting = MeetingFactory::new()->create();

        $response = $this->getJson('/meetings/'.$meeting->id);

        $response->assertStatus(200);
        $response->assertJsonFragment(['id' => $meeting->id]);
    }

    /** @test */
    public function meeting_can_be_updated(): void
    {
        $meeting = MeetingFactory::new()->create();

        $response = $this->putJson('/meetings/'.$meeting->id, [
            'status' => 'completed',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('meetings', [
            'id' => $meeting->id,
            'status' => 'completed',
        ]);
    }

    /** @test */
    public function meeting_can_be_deleted(): void
    {
        $meeting = MeetingFactory::new()->create();

        $response = $this->deleteJson('/meetings/'.$meeting->id);

        $response->assertStatus(200);
        $this->assertSoftDeleted('meetings', ['id' => $meeting->id]);
    }
}
