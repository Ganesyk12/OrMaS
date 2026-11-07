<?php

use Database\Factories\ActivityFactory;
use Database\Factories\FinanceFactory;
use Database\Factories\LetterFactory;
use Database\Factories\OrganizationFactory;
use Database\Factories\OrganizationMemberFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\LetterCategory\Models\LetterCategoryModel;
use Modules\Organization\Models\OrganizationModel;
use Tests\Concerns\AuthenticatesModuleAccess;
use Tests\TestCase;

class OrganizationAccessScopeTest extends TestCase
{
    use AuthenticatesModuleAccess, RefreshDatabase;

    private function assignUserTo(OrganizationModel $organization): void
    {
        OrganizationMemberFactory::new()->create([
            'user_id' => $this->moduleAccessUser()->id,
            'organization_id' => $organization->id,
            'is_active' => true,
        ]);

        $this->moduleAccessUser()->forgetAssignedOrganizations();
    }

    /** @test */
    public function user_without_organization_assignment_sees_all_organizations(): void
    {
        $first = OrganizationFactory::new()->create(['name' => 'Organisasi Pertama']);
        $second = OrganizationFactory::new()->create(['name' => 'Organisasi Kedua']);

        $response = $this->get('/activities/create');

        $response->assertOk();
        $response->assertSee($first->name);
        $response->assertSee($second->name);
    }

    /** @test */
    public function assigned_user_only_sees_their_organization_on_activity_create(): void
    {
        $assigned = OrganizationFactory::new()->create(['name' => 'Organisasi Saya']);
        $other = OrganizationFactory::new()->create(['name' => 'Organisasi Lain']);

        $this->assignUserTo($assigned);

        $response = $this->get('/activities/create');

        $response->assertOk();
        $response->assertSee($assigned->name);
        $response->assertDontSee($other->name);
    }

    /** @test */
    public function assigned_user_cannot_create_activity_for_another_organization(): void
    {
        $assigned = OrganizationFactory::new()->create();
        $other = OrganizationFactory::new()->create();

        $this->assignUserTo($assigned);

        $response = $this->postJson('/activities', [
            'organization_id' => $other->id,
            'title' => 'Kegiatan Organisasi Lain',
            'type' => 'sosial',
            'start_date' => '2026-11-01',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('organization_id');
        $this->assertDatabaseMissing('activities', ['title' => 'Kegiatan Organisasi Lain']);
    }

    /** @test */
    public function assigned_user_can_create_activity_for_their_organization(): void
    {
        $assigned = OrganizationFactory::new()->create();

        $this->assignUserTo($assigned);

        $response = $this->postJson('/activities', [
            'organization_id' => $assigned->id,
            'title' => 'Kegiatan Organisasi Saya',
            'type' => 'sosial',
            'start_date' => '2026-11-01',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('activities', [
            'organization_id' => $assigned->id,
            'title' => 'Kegiatan Organisasi Saya',
        ]);
    }

    /** @test */
    public function assigned_user_only_sees_activities_from_their_organization(): void
    {
        $assigned = OrganizationFactory::new()->create();
        $other = OrganizationFactory::new()->create();

        ActivityFactory::new()->create(['organization_id' => $assigned->id]);
        ActivityFactory::new()->create(['organization_id' => $other->id]);

        $this->assignUserTo($assigned);

        $response = $this->getJson('/activities');

        $response->assertOk();
        $response->assertJsonCount(1, 'data');
        $response->assertJsonPath('data.0.organization_id', $assigned->id);
    }

    /** @test */
    public function assigned_user_only_sees_their_organization_on_finance_create(): void
    {
        $assigned = OrganizationFactory::new()->create(['name' => 'Organisasi Saya']);
        $other = OrganizationFactory::new()->create(['name' => 'Organisasi Lain']);

        $this->assignUserTo($assigned);

        $response = $this->get('/finances/create');

        $response->assertOk();
        $response->assertSee($assigned->name);
        $response->assertDontSee($other->name);
    }

    /** @test */
    public function assigned_user_cannot_create_finance_for_another_organization(): void
    {
        $assigned = OrganizationFactory::new()->create();
        $other = OrganizationFactory::new()->create();

        $this->assignUserTo($assigned);

        $response = $this->postJson('/finances', [
            'organization_id' => $other->id,
            'type' => 'income',
            'transaction_date' => '2026-11-01',
            'details' => [
                ['category' => 'Sumbangan', 'amount' => 100000],
            ],
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('organization_id');
    }

    /** @test */
    public function assigned_user_only_sees_finances_from_their_organization(): void
    {
        $assigned = OrganizationFactory::new()->create();
        $other = OrganizationFactory::new()->create();

        FinanceFactory::new()->hasDetails(1)->create(['organization_id' => $assigned->id]);
        FinanceFactory::new()->hasDetails(1)->create(['organization_id' => $other->id]);

        $this->assignUserTo($assigned);

        $response = $this->getJson('/finances');

        $response->assertOk();
        $response->assertJsonCount(1, 'data');
        $response->assertJsonPath('data.0.organization_id', $assigned->id);
    }

    /** @test */
    public function assigned_user_only_sees_their_organization_on_letter_create(): void
    {
        $assigned = OrganizationFactory::new()->create(['name' => 'Organisasi Saya']);
        $other = OrganizationFactory::new()->create(['name' => 'Organisasi Lain']);

        $this->assignUserTo($assigned);

        $response = $this->get('/letters/incoming/create');

        $response->assertOk();
        $response->assertSee($assigned->name);
        $response->assertDontSee($other->name);
    }

    /** @test */
    public function assigned_user_cannot_create_letter_for_another_organization(): void
    {
        $assigned = OrganizationFactory::new()->create();
        $other = OrganizationFactory::new()->create();
        $category = LetterCategoryModel::factory()->create();

        $this->assignUserTo($assigned);

        $response = $this->postJson('/letters/incoming', [
            'category_id' => $category->id,
            'organization_id' => $other->id,
            'letter_number' => '001/UND/XI/2026',
            'subject' => 'Undangan Rapat',
            'sender' => 'Kelurahan',
            'letter_date' => '2026-11-01',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('organization_id');
    }

    /** @test */
    public function letter_creation_requires_an_organization(): void
    {
        $category = LetterCategoryModel::factory()->create();

        $response = $this->postJson('/letters/incoming', [
            'category_id' => $category->id,
            'letter_number' => '001/UND/XI/2026',
            'subject' => 'Undangan Rapat',
            'sender' => 'Kelurahan',
            'letter_date' => '2026-11-01',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('organization_id');
    }

    /** @test */
    public function assigned_user_only_sees_letters_from_their_organization(): void
    {
        $assigned = OrganizationFactory::new()->create();
        $other = OrganizationFactory::new()->create();

        LetterFactory::new()->create([
            'organization_id' => $assigned->id,
            'direction' => 'incoming',
        ]);
        LetterFactory::new()->create([
            'organization_id' => $other->id,
            'direction' => 'incoming',
        ]);

        $this->assignUserTo($assigned);

        $response = $this->getJson('/letters/incoming');

        $response->assertOk();
        $response->assertJsonCount(1, 'data');
        $response->assertJsonPath('data.0.organization_id', $assigned->id);
    }
}
