<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Finance\Models\FinanceDetailModel;
use Modules\Finance\Models\FinanceModel;

/**
 * @extends Factory<FinanceDetailModel>
 */
class FinanceDetailFactory extends Factory
{
    protected $model = FinanceDetailModel::class;

    public function definition(): array
    {
        $categories = ['Sumbangan', 'Sponsor', 'Biaya Kegiatan', 'Operasional', 'Honorarium', 'Lainnya'];

        return [
            'finance_id' => FinanceModel::factory(),
            'category' => fake()->randomElement($categories),
            'amount' => fake()->randomFloat(2, 50000, 5000000),
            'receipt' => fake()->optional()->filePath(),
            'created_by' => fake()->optional()->safeEmail(),
            'updated_by' => null,
        ];
    }
}
