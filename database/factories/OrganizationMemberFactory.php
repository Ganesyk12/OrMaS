<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Member\Models\OrganizationMemberModel;
use Modules\Organization\Models\OrganizationModel;
use Modules\Role\Models\RoleModel;

/**
 * @extends Factory<OrganizationMemberModel>
 */
class OrganizationMemberFactory extends Factory
{
    protected $model = OrganizationMemberModel::class;

    public function definition(): array
    {
        return [
            'organization_id' => OrganizationModel::factory()->create()->id,
            'user_id' => User::factory()->create()->id,
            'role_id' => RoleModel::factory()->create()->id,
            'member_number' => fake()->unique()->regexify('KT[0-9]{6}'),
            'join_date' => fake()->dateTimeBetween('-2 years', 'today')->format('Y-m-d'),
            'is_active' => true,
        ];
    }
}
