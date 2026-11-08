<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\Concerns\GrantsModuleAccess;
use Illuminate\Database\Seeder;

class SuperadminSeeder extends Seeder
{
    use GrantsModuleAccess;

    /**
     * Seed akun Superadmin dengan akses penuh ke semua modul.
     */
    public function run(): void
    {
        // Pastikan daftar modul tersedia meskipun seeder ini dijalankan sendiri.
        $this->call(ModuleSeeder::class);

        $superadmin = User::updateOrCreate(
            ['email' => 'superadmin@karlas.id'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
                'is_superadmin' => true,
            ]
        );

        $this->grantAllModuleAccess($superadmin);

        $this->command?->info('Superadmin siap: superadmin@karlas.id / password');
    }
}
