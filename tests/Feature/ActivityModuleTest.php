<?php

use Database\Factories\ActivityFactory;
use Database\Factories\OrganizationFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Activity\Models\ActivityModel;
use Tests\TestCase;

class ActivityModuleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function activity_can_be_listed(): void
    {
        $org = OrganizationFactory::new()->create();
        ActivityFactory::new()->count(3)->create(['organization_id' => $org->id]);

        $response = $this->getJson('/activities');

        $response->assertStatus(200);
        $response->assertJsonCount(3, 'data');
    }

    /** @test */
    public function activity_can_be_created(): void
    {
        $org = OrganizationFactory::new()->create();

        $response = $this->postJson('/activities', [
            'organization_id' => $org->id,
            'title' => 'Kegiatan Bakti Sosial',
            'type' => 'sosial',
            'start_date' => '2026-11-01',
            'budget' => 1000000,
            'status' => 'planning',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('activities', ['title' => 'Kegiatan Bakti Sosial']);
    }

    /** @test */
    public function activity_can_be_shown(): void
    {
        $activity = ActivityFactory::new()->create();

        $response = $this->getJson('/activities/'.$activity->id);

        $response->assertStatus(200);
        $response->assertJsonFragment(['id' => $activity->id]);
    }

    /** @test */
    public function activity_can_be_updated(): void
    {
        $activity = ActivityFactory::new()->create();

        $response = $this->putJson('/activities/'.$activity->id, [
            'status' => 'completed',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('activities', [
            'id' => $activity->id,
            'status' => 'completed',
        ]);
    }

    /** @test */
    public function activity_can_be_deleted(): void
    {
        $activity = ActivityFactory::new()->create();

        $response = $this->deleteJson('/activities/'.$activity->id);

        $response->assertStatus(200);
        $this->assertSoftDeleted('activities', ['id' => $activity->id]);
    }
}
