<?php

namespace App\Rules;

use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Modules\Organization\Models\OrganizationModel;

/**
 * Pastikan organisasi yang dipilih aktif dan boleh diakses user.
 *
 * User tanpa penugasan organisasi boleh memilih organisasi mana pun.
 */
class AccessibleOrganization implements ValidationRule
{
    public function __construct(private ?User $user) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $query = OrganizationModel::query()
            ->whereKey($value)
            ->active();

        $organizationIds = $this->user?->assignedOrganizationIds() ?? [];

        if ($organizationIds !== []) {
            $query->whereIn('organizations.id', $organizationIds);
        }

        if (! $query->exists()) {
            $fail('Organisasi yang dipilih tidak tersedia untuk akun Anda.');
        }
    }
}
