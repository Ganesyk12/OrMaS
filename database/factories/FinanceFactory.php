<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Finance\Models\FinanceDetailModel;
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

        return [
            'organization_id' => OrganizationModel::factory()->create()->id,
            'type' => fake()->randomElement($types),
            'transaction_date' => fake()->dateTimeBetween('-6 months', 'today')->format('Y-m-d'),
            'status' => fake()->randomElement($statuses),
            'created_by' => fake()->optional()->safeEmail(),
            'updated_by' => null,
        ];
    }

    /**
     * Buat detail transaksi otomatis setelah header dibuat.
     */
    public function hasDetails(int $count = 1): static
    {
        return $this->afterCreating(function (FinanceModel $finance) use ($count) {
            FinanceDetailModel::factory()->count($count)->create([
                'finance_id' => $finance->id,
            ]);
        });
    }
}
