<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\UserAccess\Models\UserAccessModel;

/**
 * @extends Factory<UserAccessModel>
 */
class UserAccessFactory extends Factory
{
    protected $model = UserAccessModel::class;

    public function definition(): array
    {
        $modules = ['organizations', 'members', 'activities', 'meetings', 'finances', 'letters', 'letter-categories'];
        $permissions = ['view', 'create', 'edit', 'delete'];

        return [
            'user_id' => User::factory()->create()->id,
            'module' => fake()->randomElement($modules),
            'permission' => fake()->randomElement($permissions),
            'is_allowed' => true,
        ];
    }
}
