<?php

namespace Modules\Shared\Concerns;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

trait BelongsToVisibleOrganization
{
    /**
     * Scope: batasi data sesuai organisasi yang ditugaskan ke user.
     *
     * Superadmin dan user tanpa penugasan tetap dapat melihat seluruh data.
     */
    public function scopeVisibleToUser(Builder $query, ?User $user): Builder
    {
        if ($user?->isSuperAdmin()) {
            return $query;
        }

        $organizationIds = $user?->assignedOrganizationIds() ?? [];

        if ($organizationIds === []) {
            return $query;
        }

        return $query->whereIn($this->getTable().'.organization_id', $organizationIds);
    }
}
