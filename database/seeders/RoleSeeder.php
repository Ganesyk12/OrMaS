<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Role\Models\RoleModel;

class RoleSeeder extends Seeder
{
    /**
     * Seed role/jabatan dasar kepengurusan.
     */
    public function run(): void
    {
        $roles = [
            ['name' => 'Ketua', 'code' => 'KETUA', 'level' => 5, 'description' => 'Ketua organisasi'],
            ['name' => 'Admin', 'code' => 'ADMIN', 'level' => 4, 'description' => 'Administrator organisasi'],
            ['name' => 'Sekretaris', 'code' => 'SEKRETARIS', 'level' => 3, 'description' => 'Sekretaris organisasi'],
            ['name' => 'Bendahara', 'code' => 'BENDAHARA', 'level' => 2, 'description' => 'Bendahara organisasi'],
            ['name' => 'Anggota', 'code' => 'ANGGOTA', 'level' => 1, 'description' => 'Anggota organisasi'],
        ];

        foreach ($roles as $role) {
            $model = RoleModel::withTrashed()->firstOrNew(['code' => $role['code']]);

            $model->fill([
                'name' => $role['name'],
                'description' => $role['description'],
                'level' => $role['level'],
                'is_active' => true,
            ]);

            // Pulihkan jika role pernah dihapus (code unik tetap berlaku).
            $model->deleted_at = null;
            $model->save();
        }
    }
}
