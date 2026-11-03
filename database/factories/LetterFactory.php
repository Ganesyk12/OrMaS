<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Letter\Models\LetterModel;
use Modules\LetterCategory\Models\LetterCategoryModel;

/**
 * @extends Factory<LetterModel>
 */
class LetterFactory extends Factory
{
    protected $model = LetterModel::class;

    public function definition(): array
    {
        $direction = fake()->randomElement(['incoming', 'outgoing']);
        $letterDate = fake()->dateTimeBetween('-6 months', 'now');

        return [
            'category_id' => LetterCategoryModel::factory(),
            'direction' => $direction,
            'letter_number' => fake()->unique()->bothify('SRT/####/XI/2026'),
            'subject' => fake()->sentence(5),
            'sender' => $direction === 'incoming' ? fake()->company() : null,
            'recipient' => $direction === 'outgoing' ? fake()->company() : null,
            'letter_date' => $letterDate->format('Y-m-d'),
            'received_date' => $direction === 'incoming'
                ? fake()->dateTimeBetween($letterDate, '+1 month')->format('Y-m-d')
                : null,
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
