<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\LetterCategory\Models\LetterCategoryModel;

/**
 * @extends Factory<LetterCategoryModel>
 */
class LetterCategoryFactory extends Factory
{
    protected $model = LetterCategoryModel::class;

    public function definition(): array
    {
        $name = fake()->unique()->words(2, true);

        return [
            'code' => strtoupper(fake()->unique()->bothify('KTG-###')),
            'name' => ucfirst($name),
            'description' => fake()->optional()->sentence(),
            'is_active' => true,
        ];
    }
}
