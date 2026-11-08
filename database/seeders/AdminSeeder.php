<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\Concerns\GrantsModuleAccess;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    use GrantsModuleAccess;

    /**
     * Seed akun Admin dengan akses penuh ke semua modul.
     */
    public function run(): void
    {
        // Pastikan daftar modul tersedia meskipun seeder ini dijalankan sendiri.
        $this->call(ModuleSeeder::class);

        $admin = User::updateOrCreate(
            ['email' => 'admin@karlas.id'],
            [
                'name' => 'Admin',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
                'is_superadmin' => false,
            ]
        );

        $this->grantAllModuleAccess($admin);

        $this->command?->info('Admin siap: admin@karlas.id / password');
    }
}
