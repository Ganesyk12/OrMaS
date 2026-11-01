<?php

namespace Modules\Organization\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Database\Factories\OrganizationFactory;
use Modules\Member\Models\OrganizationMemberModel;
use Modules\Activity\Models\ActivityModel;

class OrganizationModel extends Model
{
    use HasFactory, SoftDeletes;

    protected static function newFactory()
    {
        return new OrganizationFactory;
    }
    protected $table = 'organizations';

    protected $fillable = [
        'name',
        'code',
        'type',
        'level',
        'address',
        'description',
        'founded_at',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'founded_at' => 'datetime',
    ];

    protected $attributes = [
        'is_active' => true,
    ];

    /**
     * Relasi: satu organisasi memiliki banyak anggota.
     */
    public function members(): HasMany
    {
        return $this->hasMany(OrganizationMemberModel::class, 'organization_id');
    }

    /**
     * Relasi: satu organisasi memiliki banyak kegiatan.
     */
    public function activities(): HasMany
    {
        return $this->hasMany(ActivityModel::class, 'organization_id');
    }

    /**
     * Scope: hanya organisasi yang aktif.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: filter berdasarkan tipe organisasi (rt, rw, kelurahan, kecamatan).
     */
    public function scopeType($query, string $type)
    {
        return $query->where('type', $type);
    }
}
