<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Organization\Models\OrganizationModel;

/**
 * @extends Factory<OrganizationModel>
 */
class OrganizationFactory extends Factory
{
    protected $model = OrganizationModel::class;

    public function definition(): array
    {
        $types = ['rt', 'rw', 'kelurahan', 'kecamatan'];

        return [
            'name' => fake()->unique()->company().' Karang Taruna',
            'code' => fake()->unique()->regexify('[A-Z]{2}[0-9]{3}'),
            'type' => fake()->randomElement($types),
            'level' => fake()->optional()->word(),
            'address' => fake()->optional()->address(),
            'description' => fake()->optional()->sentence(),
            'founded_at' => fake()->optional()->date(),
            'is_active' => true,
        ];
    }
}
