<?php

namespace Database\Seeders\Concerns;

use App\Models\User;
use Modules\UserAccess\Models\ModuleModel;
use Modules\UserAccess\Models\UserAccessModel;

trait GrantsModuleAccess
{
    /**
     * Beri akses penuh ke seluruh modul aktif.
     */
    protected function grantAllModuleAccess(User $user): void
    {
        foreach (ModuleModel::activeNames() as $module) {
            $access = UserAccessModel::withTrashed()->firstOrNew([
                'user_id' => $user->id,
                'module' => $module,
            ]);

            $access->fill([
                'allow_view' => true,
                'allow_create' => true,
                'allow_edit' => true,
                'allow_delete' => true,
            ]);

            // Pulihkan jika sebelumnya pernah dihapus (unique index tetap berlaku).
            $access->deleted_at = null;
            $access->save();
        }
    }
}
