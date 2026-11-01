<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Agenda\Models\AgendaModel;
use Modules\Meeting\Models\MeetingModel;

/**
 * @extends Factory<AgendaModel>
 */
class AgendaFactory extends Factory
{
    protected $model = AgendaModel::class;

    public function definition(): array
    {
        $statuses = ['pending', 'discussed', 'completed', 'skipped'];

        return [
            'meeting_id' => MeetingModel::factory()->create()->id,
            'title' => fake()->sentence(4),
            'description' => fake()->optional()->sentence(),
            'presenter' => fake()->optional()->name(),
            'duration_minutes' => fake()->optional()->numberBetween(5, 120),
            'order' => fake()->numberBetween(1, 10),
            'status' => fake()->randomElement($statuses),
        ];
    }
}
