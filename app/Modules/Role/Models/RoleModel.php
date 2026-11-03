<?php

namespace Modules\Role\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Database\Factories\RoleFactory;
use Modules\Member\Models\OrganizationMemberModel;

use Illuminate\Support\Str;

class RoleModel extends Model
{
    protected static function booted()
    {
        static::creating(function ($role) {
            if (empty($role->code)) {
                $role->code = 'RL' . strtoupper(Str::random(4));
            }
        });
    }
{
    use HasFactory, SoftDeletes;

    protected static function newFactory()
    {
        return new RoleFactory;
    }
    protected $table = 'roles';

    protected $fillable = [
        'name',
        'code',
        'description',
        'level',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'level' => 'integer',
    ];

    protected $attributes = [
        'is_active' => true,
        'level' => 0,
    ];

    /**
     * Relasi: satu role dapat dipegang oleh banyak anggota.
     */
    public function members(): HasMany
    {
        return $this->hasMany(OrganizationMemberModel::class, 'role_id');
    }

    /**
     * Scope: hanya role yang aktif.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: urutkan berdasarkan level (jabatan tertama terlebih dahulu).
     */
    public function scopeByLevel($query, string $direction = 'asc')
    {
        return $query->orderBy('level', $direction);
    }
}
