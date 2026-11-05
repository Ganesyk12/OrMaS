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

        return [
            'user_id' => User::factory()->create()->id,
            'module' => fake()->randomElement($modules),
            'allow_view' => true,
            'allow_create' => fake()->boolean(),
            'allow_edit' => fake()->boolean(),
            'allow_delete' => fake()->boolean(),
        ];
    }
}
