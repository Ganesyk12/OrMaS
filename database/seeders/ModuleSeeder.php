<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\UserAccess\Models\ModuleModel;

class ModuleSeeder extends Seeder
{
    /**
     * Seed daftar modul aplikasi.
     */
    public function run(): void
    {
        $modules = [
            ['name' => 'organizations', 'label' => 'Organisasi'],
            ['name' => 'members', 'label' => 'Anggota'],
            ['name' => 'roles', 'label' => 'Role / Jabatan'],
            ['name' => 'users', 'label' => 'Pengguna'],
            ['name' => 'user-accesses', 'label' => 'Hak Akses'],
            ['name' => 'activities', 'label' => 'Kegiatan'],
            ['name' => 'meetings', 'label' => 'Rapat'],
            ['name' => 'agendas', 'label' => 'Agenda'],
            ['name' => 'finances', 'label' => 'Keuangan'],
            ['name' => 'letters', 'label' => 'Surat'],
            ['name' => 'letter-categories', 'label' => 'Kategori Surat'],
        ];

        foreach ($modules as $index => $module) {
            ModuleModel::updateOrCreate(
                ['name' => $module['name']],
                [
                    'label' => $module['label'],
                    'sort_order' => $index + 1,
                    'is_active' => true,
                ]
            );
        }
    }
}
