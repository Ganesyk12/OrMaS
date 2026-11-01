<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Factories\ActivityFactory;
use Database\Factories\AgendaFactory;
use Database\Factories\FinanceFactory;
use Database\Factories\OrganizationFactory;
use Database\Factories\OrganizationMemberFactory;
use Database\Factories\RoleFactory;
use Database\Factories\UserAccessFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Modules\Activity\Models\ActivityModel;
use Modules\Agenda\Models\AgendaModel;
use Modules\Finance\Models\FinanceModel;
use Modules\Meeting\Models\MeetingModel;
use Modules\Member\Models\OrganizationMemberModel;
use Modules\Organization\Models\OrganizationModel;
use Modules\Role\Models\RoleModel;
use Modules\UserAccess\Models\UserAccessModel;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Super admin (idempotent)
        $admin = User::updateOrCreate(
            ['email' => 'admin@karangtaruna.id'],
            ['name' => 'Administrator', 'password' => bcrypt('password')]
        );

        // Organisasi contoh
        $org = OrganizationModel::factory()->create([
            'name' => 'Karang Taruna RW 05',
            'code' => 'KTRW05',
            'type' => 'rw',
            'address' => 'Jl. Merdeka No. 10, Kelurahan Sentral',
            'is_active' => true,
        ]);

        // Role jabatan kepengurusan
        $roles = [
            RoleModel::factory()->create(['name' => 'Ketua', 'code' => 'KETUA', 'level' => 1]),
            RoleModel::factory()->create(['name' => 'Sekretaris', 'code' => 'SEKRETARIS', 'level' => 3]),
            RoleModel::factory()->create(['name' => 'Bendahara', 'code' => 'BENDAHARA', 'level' => 4]),
            RoleModel::factory()->create(['name' => 'Anggota', 'code' => 'ANGGOTA', 'level' => 10]),
        ];

        // Anggota contoh (termasuk admin sebagai anggota)
        $member = OrganizationMemberModel::factory()->create([
            'organization_id' => $org->id,
            'user_id' => $admin->id,
            'role_id' => $roles[0]->id, // Ketua
            'member_number' => 'KT000001',
        ]);

        User::factory(5)->create()->each(function (User $user) use ($org, $roles) {
            OrganizationMemberModel::factory()->create([
                'organization_id' => $org->id,
                'user_id' => $user->id,
                'role_id' => $roles[array_rand($roles)]->id,
            ]);
        });

        // Hak akses admin
        foreach (['organizations', 'members', 'roles', 'activities', 'meetings', 'finances'] as $module) {
            foreach (['view', 'create', 'edit', 'delete'] as $permission) {
                UserAccessModel::factory()->create([
                    'user_id' => $admin->id,
                    'module' => $module,
                    'permission' => $permission,
                ]);
            }
        }

        // Kegiatan contoh
        $activity = ActivityModel::factory()->create([
            'organization_id' => $org->id,
            'title' => 'Bakti Sosial Bersih Desa',
            'type' => 'sosial',
            'status' => 'completed',
        ]);

        // Rapat contoh dengan agenda
        $meeting = MeetingModel::factory()->create([
            'organization_id' => $org->id,
            'title' => 'Rapat Kerja Bulanan',
            'status' => 'completed',
        ]);

        AgendaModel::factory()->count(4)->create([
            'meeting_id' => $meeting->id,
        ])->each(fn (AgendaModel $agenda) => $agenda->update(['order' => $agenda->id]));

        // Keuangan contoh
        FinanceModel::factory()->create([
            'organization_id' => $org->id,
            'activity_id' => $activity->id,
            'type' => 'income',
            'category' => 'Sumbangan',
            'amount' => 2500000,
            'status' => 'approved',
        ]);

        FinanceModel::factory()->create([
            'organization_id' => $org->id,
            'activity_id' => $activity->id,
            'type' => 'expense',
            'category' => 'Biaya Kegiatan',
            'amount' => 750000,
            'status' => 'approved',
        ]);

        $this->command->info('Database seeding selesai. Admin: admin@karangtaruna.id / password');
    }
}
