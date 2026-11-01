<?php

use App\Models\User;
use Database\Factories\OrganizationFactory;
use Database\Factories\OrganizationMemberFactory;
use Database\Factories\RoleFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Member\Models\OrganizationMemberModel;
use Tests\TestCase;

class MemberModuleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function member_can_be_listed(): void
    {
        $org = OrganizationFactory::new()->create();
        RoleFactory::new()->create();
        OrganizationMemberFactory::new()->count(3)->create(['organization_id' => $org->id]);

        $response = $this->getJson('/members');

        $response->assertStatus(200);
        $response->assertJsonCount(3, 'data');
    }

    /** @test */
    public function member_can_be_created(): void
    {
        $org = OrganizationFactory::new()->create();
        $user = User::factory()->create();
        $role = RoleFactory::new()->create();

        $response = $this->postJson('/members', [
            'organization_id' => $org->id,
            'user_id' => $user->id,
            'role_id' => $role->id,
            'member_number' => 'KT000100',
            'join_date' => '2026-01-01',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('organization_members', ['member_number' => 'KT000100']);
    }

    /** @test */
    public function member_can_be_shown(): void
    {
        $member = OrganizationMemberFactory::new()->create();

        $response = $this->getJson('/members/'.$member->id);

        $response->assertStatus(200);
        $response->assertJsonFragment(['id' => $member->id]);
    }

    /** @test */
    public function member_can_be_updated(): void
    {
        $member = OrganizationMemberFactory::new()->create();

        $response = $this->putJson('/members/'.$member->id, [
            'member_number' => 'KT000200',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('organization_members', [
            'id' => $member->id,
            'member_number' => 'KT000200',
        ]);
    }

    /** @test */
    public function member_can_be_deleted(): void
    {
        $member = OrganizationMemberFactory::new()->create();

        $response = $this->deleteJson('/members/'.$member->id);

        $response->assertStatus(200);
        $this->assertSoftDeleted('organization_members', ['id' => $member->id]);
    }
}
