<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Meeting\Models\MeetingModel;
use Modules\Organization\Models\OrganizationModel;

/**
 * @extends Factory<MeetingModel>
 */
class MeetingFactory extends Factory
{
    protected $model = MeetingModel::class;

    public function definition(): array
    {
        $statuses = ['scheduled', 'completed', 'cancelled'];
        $date = fake()->dateTimeBetween('-3 months', '+3 months');

        return [
            'organization_id' => OrganizationModel::factory()->create()->id,
            'title' => fake()->sentence(4),
            'description' => fake()->optional()->paragraph(),
            'meeting_date' => $date,
            'location' => fake()->optional()->address(),
            'status' => fake()->randomElement($statuses),
        ];
    }
}
