<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed data dasar aplikasi.
     *
     * Fokus awal: modul, role/jabatan, akun superadmin & admin, serta hak aksesnya.
     * Data contoh organisasi/kegiatan/keuangan belum di-seed di tahap ini.
     */
    public function run(): void
    {
        $this->call([
            ModuleSeeder::class,
            RoleSeeder::class,
            SuperadminSeeder::class,
            AdminSeeder::class,
        ]);

        $this->command?->info('Seeding selesai.');
        $this->command?->info('Superadmin: superadmin@karlas.id / password');
        $this->command?->info('Admin     : admin@karlas.id / password');
    }
}
