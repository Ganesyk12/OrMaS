<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Role\Models\RoleModel;
use Modules\UserAccess\Models\ModuleModel;
use Modules\UserAccess\Models\UserAccessModel;
use Tests\TestCase;

class DatabaseSeederTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function database_seeder_creates_superadmin_admin_roles_and_access(): void
    {
        $this->seed();

        $superadmin = User::where('email', 'superadmin@karlas.id')->firstOrFail();
        $admin = User::where('email', 'admin@karlas.id')->firstOrFail();

        $this->assertNotNull($superadmin->email_verified_at);
        $this->assertNotNull($admin->email_verified_at);

        $this->assertTrue($superadmin->isSuperAdmin());
        $this->assertFalse($admin->isSuperAdmin());

        $expectedRoles = ['KETUA', 'ADMIN', 'SEKRETARIS', 'BENDAHARA', 'ANGGOTA'];

        $this->assertSame(count($expectedRoles), RoleModel::whereIn('code', $expectedRoles)->count());

        $modules = ModuleModel::active()->pluck('name');

        foreach ([$superadmin, $admin] as $user) {
            $accesses = UserAccessModel::where('user_id', $user->id)->get();

            $this->assertCount($modules->count(), $accesses);

            foreach ($accesses as $access) {
                $this->assertTrue($access->allow_view);
                $this->assertTrue($access->allow_create);
                $this->assertTrue($access->allow_edit);
                $this->assertTrue($access->allow_delete);
            }
        }
    }

    /** @test */
    public function database_seeder_is_idempotent(): void
    {
        $this->seed();
        $this->seed();

        $this->assertSame(2, User::whereIn('email', [
            'superadmin@karlas.id',
            'admin@karlas.id',
        ])->count());

        $this->assertSame(5, RoleModel::count());
        $this->assertSame(ModuleModel::active()->count() * 2, UserAccessModel::count());
    }
}
