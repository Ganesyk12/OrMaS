<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Role\Models\RoleModel;

/**
 * @extends Factory<RoleModel>
 */
class RoleFactory extends Factory
{
    protected $model = RoleModel::class;

    public function definition(): array
    {
        $roles = [
            ['name' => 'Ketua Umum', 'code' => 'KETUA', 'level' => 1],
            ['name' => 'Wakil Ketua', 'code' => 'WKETUA', 'level' => 2],
            ['name' => 'Sekretaris', 'code' => 'SEKRETARIS', 'level' => 3],
            ['name' => 'Bendahara', 'code' => 'BENDAHARA', 'level' => 4],
            ['name' => 'Anggota', 'code' => 'ANGGOTA', 'level' => 10],
        ];
        $data = fake()->randomElement($roles);

        return [
            'name' => $data['name'],
            'code' => fake()->unique()->regexify('[A-Z]{3,10}'),
            'description' => fake()->optional()->sentence(),
            'level' => $data['level'],
            'is_active' => true,
        ];
    }
}
