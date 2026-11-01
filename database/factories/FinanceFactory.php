<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Finance\Models\FinanceModel;
use Modules\Organization\Models\OrganizationModel;

/**
 * @extends Factory<FinanceModel>
 */
class FinanceFactory extends Factory
{
    protected $model = FinanceModel::class;

    public function definition(): array
    {
        $types = ['income', 'expense'];
        $statuses = ['draft', 'approved', 'rejected'];
        $categories = ['Sumbangan', 'Sponsor', 'Biaya Kegiatan', 'Operasional', 'Honorarium', 'Lainnya'];

        return [
            'organization_id' => OrganizationModel::factory()->create()->id,
            'type' => fake()->randomElement($types),
            'category' => fake()->randomElement($categories),
            'amount' => fake()->randomFloat(2, 50000, 5000000),
            'description' => fake()->optional()->sentence(),
            'transaction_date' => fake()->dateTimeBetween('-6 months', 'today')->format('Y-m-d'),
            'receipt' => fake()->optional()->filePath(),
            'status' => fake()->randomElement($statuses),
        ];
    }
}
