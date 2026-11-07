<?php

namespace Modules\Shared\Concerns;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

trait BelongsToVisibleOrganization
{
    /**
     * Scope: batasi data sesuai organisasi yang ditugaskan ke user.
     *
     * User tanpa penugasan tetap dapat melihat seluruh data.
     */
    public function scopeVisibleToUser(Builder $query, ?User $user): Builder
    {
        $organizationIds = $user?->assignedOrganizationIds() ?? [];

        if ($organizationIds === []) {
            return $query;
        }

        return $query->whereIn($this->getTable().'.organization_id', $organizationIds);
    }
}
