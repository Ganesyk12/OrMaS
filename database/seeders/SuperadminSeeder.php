<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Modules\UserAccess\Models\ModuleModel;
use Modules\UserAccess\Models\UserAccessModel;

class SuperadminSeeder extends Seeder
{
    /**
     * Seed akun Superadmin dengan akses penuh ke semua modul.
     */
    public function run(): void
    {
        // Pastikan daftar modul tersedia meskipun seeder ini dijalankan sendiri.
        $this->call(ModuleSeeder::class);

        $superadmin = User::updateOrCreate(
            ['email' => 'superadmin@karangtaruna.id'],
            [
                'name' => 'Superadmin',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );

        foreach (ModuleModel::activeNames() as $module) {
            UserAccessModel::updateOrCreate(
                ['user_id' => $superadmin->id, 'module' => $module],
                [
                    'allow_view' => true,
                    'allow_create' => true,
                    'allow_edit' => true,
                    'allow_delete' => true,
                ]
            );
        }

        $this->command?->info('Superadmin siap: superadmin@karangtaruna.id / password');
    }
}
