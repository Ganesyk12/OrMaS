<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Activity\Models\ActivityModel;
use Modules\Organization\Models\OrganizationModel;

/**
 * @extends Factory<ActivityModel>
 */
class ActivityFactory extends Factory
{
    protected $model = ActivityModel::class;

    public function definition(): array
    {
        $types = ['program', 'kegiatan', 'pelatihan', 'sosial'];
        $statuses = ['planning', 'ongoing', 'completed', 'cancelled'];
        $start = fake()->dateTimeBetween('-6 months', '+3 months')->format('Y-m-d');

        return [
            'organization_id' => OrganizationModel::factory()->create()->id,
            'title' => fake()->sentence(4),
            'description' => fake()->optional()->paragraph(),
            'type' => fake()->randomElement($types),
            'start_date' => $start,
            'end_date' => fake()->optional()->dateTimeBetween($start, '+6 months')?->format('Y-m-d'),
            'location' => fake()->optional()->address(),
            'budget' => fake()->randomFloat(2, 100000, 50000000),
            'status' => fake()->randomElement($statuses),
        ];
    }
}
