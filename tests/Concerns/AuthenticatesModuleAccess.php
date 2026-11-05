<?php

namespace Tests\Concerns;

use App\Models\User;
use Modules\UserAccess\Models\ModuleModel;
use Modules\UserAccess\Models\UserAccessModel;

/**
 * Login otomatis sebagai pengguna dengan akses penuh ke semua modul.
 *
 * Trait ini dipakai test modul supaya request tidak tertahan middleware
 * module.access. Method setUp* akan dipanggil otomatis oleh Laravel.
 */
trait AuthenticatesModuleAccess
{
    protected ?User $moduleAccessUser = null;

    protected function setUpAuthenticatesModuleAccess(): void
    {
        $this->moduleAccessUser = User::factory()->create();

        foreach (ModuleModel::activeNames() as $module) {
            UserAccessModel::create([
                'user_id' => $this->moduleAccessUser->id,
                'module' => $module,
                'allow_view' => true,
                'allow_create' => true,
                'allow_edit' => true,
                'allow_delete' => true,
            ]);
        }

        $this->actingAs($this->moduleAccessUser);
    }

    protected function moduleAccessUser(): User
    {
        return $this->moduleAccessUser;
    }
}
