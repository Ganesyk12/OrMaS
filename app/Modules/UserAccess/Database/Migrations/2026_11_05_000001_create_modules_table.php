<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('label');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

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
            DB::table('modules')->updateOrInsert(
                ['name' => $module['name']],
                [
                    'label' => $module['label'],
                    'sort_order' => $index + 1,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modules');
    }
};
