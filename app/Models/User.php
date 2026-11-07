<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Modules\Member\Models\OrganizationMemberModel;
use Modules\Organization\Models\OrganizationModel;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Cache organisasi yang ditugaskan selama satu request.
     *
     * @var array<int, int>|null
     */
    protected ?array $assignedOrganizationIdsCache = null;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relasi: keanggotaan organisasi milik user.
     */
    public function organizationMemberships(): HasMany
    {
        return $this->hasMany(OrganizationMemberModel::class, 'user_id');
    }

    /**
     * Relasi: organisasi tempat user terdaftar sebagai anggota aktif.
     */
    public function assignedOrganizations(): BelongsToMany
    {
        return $this->belongsToMany(
            OrganizationModel::class,
            'organization_members',
            'user_id',
            'organization_id'
        )
            ->wherePivot('is_active', true)
            ->wherePivotNull('deleted_at')
            ->withPivot(['role_id', 'is_active']);
    }

    /**
     * ID organisasi aktif yang ditugaskan ke user.
     *
     * @return array<int, int>
     */
    public function assignedOrganizationIds(): array
    {
        if ($this->assignedOrganizationIdsCache === null) {
            $this->assignedOrganizationIdsCache = $this->assignedOrganizations()
                ->pluck('organizations.id')
                ->map(fn ($id) => (int) $id)
                ->all();
        }

        return $this->assignedOrganizationIdsCache;
    }

    /**
     * Apakah user sudah ditugaskan ke organisasi tertentu.
     */
    public function hasAssignedOrganizations(): bool
    {
        return $this->assignedOrganizationIds() !== [];
    }

    /**
     * Hapus cache organisasi (dipakai setelah keanggotaan berubah).
     */
    public function forgetAssignedOrganizations(): void
    {
        $this->assignedOrganizationIdsCache = null;
    }
}
