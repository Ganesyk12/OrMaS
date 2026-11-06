<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Modules\Activity\Models\ActivityModel;
use Modules\Agenda\Models\AgendaModel;
use Modules\Finance\Models\FinanceModel;
use Modules\Letter\Models\LetterModel;
use Modules\LetterCategory\Models\LetterCategoryModel;
use Modules\Meeting\Models\MeetingModel;
use Modules\Member\Models\OrganizationMemberModel;
use Modules\Organization\Models\OrganizationModel;
use Modules\Role\Models\RoleModel;
use Modules\UserAccess\Models\ModuleModel;
use Modules\UserAccess\Models\UserAccessModel;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(ModuleSeeder::class);
        $this->call(SuperadminSeeder::class);

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

        // Hak akses admin: semua modul aktif, semua permission.
        foreach (ModuleModel::activeNames() as $module) {
            UserAccessModel::updateOrCreate(
                ['user_id' => $admin->id, 'module' => $module],
                [
                    'allow_view' => true,
                    'allow_create' => true,
                    'allow_edit' => true,
                    'allow_delete' => true,
                ]
            );
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

        // Keuangan contoh (header + detail)
        $income = FinanceModel::factory()->create([
            'organization_id' => $org->id,
            'activity_id' => $activity->id,
            'type' => 'income',
            'status' => 'approved',
            'created_by' => $admin->email,
        ]);
        $income->details()->create([
            'category' => 'Sumbangan',
            'amount' => 2500000,
            'created_by' => $admin->email,
        ]);

        $expense = FinanceModel::factory()->create([
            'organization_id' => $org->id,
            'activity_id' => $activity->id,
            'type' => 'expense',
            'status' => 'approved',
            'created_by' => $admin->email,
        ]);
        $expense->details()->create([
            'category' => 'Biaya Kegiatan',
            'amount' => 750000,
            'created_by' => $admin->email,
        ]);

        // Kategori surat (master data)
        $letterCategories = collect([
            ['code' => 'UND', 'name' => 'Undangan', 'description' => 'Surat undangan kegiatan atau rapat'],
            ['code' => 'PMB', 'name' => 'Pemberitahuan', 'description' => 'Surat pemberitahuan informasi'],
            ['code' => 'PMH', 'name' => 'Permohonan', 'description' => 'Surat permohonan bantuan atau kerja sama'],
            ['code' => 'LAP', 'name' => 'Laporan', 'description' => 'Surat laporan kegiatan'],
        ])->mapWithKeys(function (array $data) {
            $category = LetterCategoryModel::firstOrCreate(
                ['code' => $data['code']],
                $data + ['is_active' => true]
            );

            return [$data['code'] => $category];
        });

        // Contoh surat masuk & keluar
        LetterModel::updateOrCreate(
            ['direction' => 'incoming', 'letter_number' => '001/UND/XI/2026'],
            [
                'category_id' => $letterCategories['UND']->id,
                'subject' => 'Undangan Rapat Koordinasi Karang Taruna',
                'sender' => 'Kelurahan Sentral',
                'recipient' => null,
                'letter_date' => '2026-11-01',
                'received_date' => '2026-11-02',
                'notes' => 'Rapat dilaksanakan di aula kelurahan.',
            ]
        );

        LetterModel::updateOrCreate(
            ['direction' => 'outgoing', 'letter_number' => '002/PMH/XI/2026'],
            [
                'category_id' => $letterCategories['PMH']->id,
                'subject' => 'Permohonan Bantuan Dana Kegiatan',
                'sender' => null,
                'recipient' => 'Pemerintah Kota',
                'letter_date' => '2026-11-03',
                'received_date' => null,
                'notes' => 'Ditujukan kepada bagian pemberdayaan masyarakat.',
            ]
        );

        $this->command->info('Database seeding selesai. Admin: admin@karangtaruna.id / password');
    }
}
