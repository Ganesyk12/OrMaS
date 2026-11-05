<?php

use Database\Factories\FinanceFactory;
use Database\Factories\OrganizationFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Finance\Models\FinanceModel;
use Tests\Concerns\AuthenticatesModuleAccess;
use Tests\TestCase;

class FinanceModuleTest extends TestCase
{
    use AuthenticatesModuleAccess, RefreshDatabase;

    /** @test */
    public function finance_can_be_listed(): void
    {
        $org = OrganizationFactory::new()->create();
        FinanceFactory::new()->count(3)->create(['organization_id' => $org->id]);

        $response = $this->getJson('/finances');

        $response->assertStatus(200);
        $response->assertJsonCount(3, 'data');
    }

    /** @test */
    public function finance_can_be_created(): void
    {
        $org = OrganizationFactory::new()->create();

        $response = $this->postJson('/finances', [
            'organization_id' => $org->id,
            'type' => 'income',
            'category' => 'Sumbangan',
            'amount' => 5000000,
            'transaction_date' => '2026-11-01',
            'status' => 'draft',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('finances', ['category' => 'Sumbangan']);
    }

    /** @test */
    public function finance_can_be_shown(): void
    {
        $finance = FinanceFactory::new()->create();

        $response = $this->getJson('/finances/'.$finance->id);

        $response->assertStatus(200);
        $response->assertJsonFragment(['id' => $finance->id]);
    }

    /** @test */
    public function finance_can_be_updated(): void
    {
        $finance = FinanceFactory::new()->create();

        $response = $this->putJson('/finances/'.$finance->id, [
            'status' => 'approved',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('finances', [
            'id' => $finance->id,
            'status' => 'approved',
        ]);
    }

    /** @test */
    public function finance_can_be_deleted(): void
    {
        $finance = FinanceFactory::new()->create();

        $response = $this->deleteJson('/finances/'.$finance->id);

        $response->assertStatus(200);
        $this->assertSoftDeleted('finances', ['id' => $finance->id]);
    }
}
