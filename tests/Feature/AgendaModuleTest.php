<?php

use Database\Factories\AgendaFactory;
use Database\Factories\MeetingFactory;
use Database\Factories\OrganizationFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Agenda\Models\AgendaModel;
use Tests\TestCase;

class AgendaModuleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function agenda_can_be_listed_under_a_meeting(): void
    {
        $org = OrganizationFactory::new()->create();
        $meeting = MeetingFactory::new()->create(['organization_id' => $org->id]);
        AgendaFactory::new()->count(3)->create(['meeting_id' => $meeting->id]);

        $response = $this->getJson('/meetings/'.$meeting->id.'/agendas');

        $response->assertStatus(200);
        $response->assertJsonCount(3, 'data');
    }

    /** @test */
    public function agenda_can_be_created_under_a_meeting(): void
    {
        $org = OrganizationFactory::new()->create();
        $meeting = MeetingFactory::new()->create(['organization_id' => $org->id]);

        $response = $this->postJson('/meetings/'.$meeting->id.'/agendas', [
            'title' => 'Pembukaan',
            'presenter' => 'Ketua',
            'duration_minutes' => 15,
            'order' => 1,
            'status' => 'pending',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('agendas', ['title' => 'Pembukaan', 'meeting_id' => $meeting->id]);
    }

    /** @test */
    public function agenda_can_be_shown(): void
    {
        $agenda = AgendaFactory::new()->create();

        $response = $this->getJson('/meetings/'.$agenda->meeting_id.'/agendas/'.$agenda->id);

        $response->assertStatus(200);
        $response->assertJsonFragment(['id' => $agenda->id]);
    }

    /** @test */
    public function agenda_can_be_updated(): void
    {
        $agenda = AgendaFactory::new()->create();

        $response = $this->putJson('/meetings/'.$agenda->meeting_id.'/agendas/'.$agenda->id, [
            'status' => 'completed',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('agendas', [
            'id' => $agenda->id,
            'status' => 'completed',
        ]);
    }

    /** @test */
    public function agenda_can_be_deleted(): void
    {
        $agenda = AgendaFactory::new()->create();

        $response = $this->deleteJson('/meetings/'.$agenda->meeting_id.'/agendas/'.$agenda->id);

        $response->assertStatus(200);
        $this->assertSoftDeleted('agendas', ['id' => $agenda->id]);
    }
}
