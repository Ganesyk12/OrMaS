<?php

use Database\Factories\FinanceFactory;
use Database\Factories\OrganizationFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\AuthenticatesModuleAccess;
use Tests\TestCase;

class FinanceModuleTest extends TestCase
{
    use AuthenticatesModuleAccess, RefreshDatabase;

    /** @test */
    public function finance_can_be_listed(): void
    {
        $org = OrganizationFactory::new()->create();
        FinanceFactory::new()->hasDetails(2)->count(3)->create(['organization_id' => $org->id]);

        $response = $this->getJson('/finances');

        $response->assertStatus(200);
        $response->assertJsonCount(3, 'data');
        $response->assertJsonStructure([
            'data' => [
                ['id', 'type', 'total'],
            ],
        ]);
    }

    /** @test */
    public function finance_can_be_created_with_details(): void
    {
        $org = OrganizationFactory::new()->create();

        $response = $this->postJson('/finances', [
            'organization_id' => $org->id,
            'type' => 'income',
            'transaction_date' => '2026-11-01',
            'status' => 'draft',
            'details' => [
                ['category' => 'Sumbangan', 'amount' => 5000000],
                ['category' => 'Sponsor', 'amount' => 1500000],
            ],
        ]);

        $response->assertStatus(201);
        $response->assertJsonPath('data.total', 6500000);

        $this->assertDatabaseHas('finance_details', ['category' => 'Sumbangan', 'amount' => 5000000]);
        $this->assertDatabaseHas('finances', [
            'status' => 'draft',
            'created_by' => $this->moduleAccessUser()->email,
        ]);
    }

    /** @test */
    public function finance_requires_at_least_one_detail(): void
    {
        $org = OrganizationFactory::new()->create();

        $response = $this->postJson('/finances', [
            'organization_id' => $org->id,
            'type' => 'income',
            'transaction_date' => '2026-11-01',
            'details' => [],
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('details');
    }

    /** @test */
    public function finance_can_be_shown_with_details(): void
    {
        $finance = FinanceFactory::new()->hasDetails(2)->create();

        $response = $this->getJson('/finances/'.$finance->id);

        $response->assertStatus(200);
        $response->assertJsonFragment(['id' => $finance->id]);
        $response->assertJsonCount(2, 'data.details');
    }

    /** @test */
    public function finance_header_can_be_updated_without_touching_details(): void
    {
        $finance = FinanceFactory::new()->hasDetails(1)->create();

        $response = $this->putJson('/finances/'.$finance->id, [
            'status' => 'approved',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('finances', [
            'id' => $finance->id,
            'status' => 'approved',
            'updated_by' => $this->moduleAccessUser()->email,
        ]);
        $this->assertDatabaseCount('finance_details', 1);
    }

    /** @test */
    public function finance_details_can_be_synced_on_update(): void
    {
        $finance = FinanceFactory::new()->hasDetails(1)->create();
        $detail = $finance->details()->first();

        $response = $this->putJson('/finances/'.$finance->id, [
            'details' => [
                ['id' => $detail->id, 'category' => 'Diperbarui', 'amount' => 2000000],
                ['category' => 'Detail Baru', 'amount' => 500000],
            ],
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('finance_details', [
            'id' => $detail->id,
            'category' => 'Diperbarui',
            'amount' => 2000000,
        ]);
        $this->assertDatabaseHas('finance_details', [
            'finance_id' => $finance->id,
            'category' => 'Detail Baru',
            'amount' => 500000,
        ]);
        $this->assertSame(2, $finance->details()->count());
    }

    /** @test */
    public function finance_and_its_details_can_be_deleted(): void
    {
        $finance = FinanceFactory::new()->hasDetails(2)->create();

        $response = $this->deleteJson('/finances/'.$finance->id);

        $response->assertStatus(200);
        $this->assertSoftDeleted('finances', ['id' => $finance->id]);
        $this->assertSoftDeleted('finance_details', ['finance_id' => $finance->id]);
    }

    /** @test */
    public function finance_create_page_renders_detail_fields(): void
    {
        $this->get('/finances/create')
            ->assertOk()
            ->assertSee('Detail Transaksi')
            ->assertSee('details[0][category]', false)
            ->assertSee('details[0][amount]', false)
            ->assertSee('details[0][receipt]', false);
    }

    /** @test */
    public function finance_show_page_renders_details_and_total(): void
    {
        $finance = FinanceFactory::new()->hasDetails(2)->create();
        $detail = $finance->details()->first();

        $this->get('/finances/'.$finance->id)
            ->assertOk()
            ->assertSee('Rincian Transaksi')
            ->assertSee($detail->category)
            ->assertSee('Rp '.number_format($finance->total, 0, ',', '.'));
    }
}
