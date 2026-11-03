<?php

namespace Modules\Member\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Database\Factories\OrganizationMemberFactory;
use Modules\Organization\Models\OrganizationModel;
use Modules\Role\Models\RoleModel;
use App\Models\User;

use Illuminate\Support\Str;

class OrganizationMemberModel extends Model
{
    protected static function booted()
    {
        static::creating(function ($member) {
            if (empty($member->member_number)) {
                $member->member_number = 'MEMB' . strtoupper(Str::random(4));
            }
        });
    }
{
    use HasFactory, SoftDeletes;

    protected static function newFactory()
    {
        return new OrganizationMemberFactory;
    }
    protected $table = 'organization_members';

    protected $fillable = [
        'organization_id',
        'user_id',
        'role_id',
        'member_number',
        'join_date',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'join_date' => 'date',
    ];

    protected $attributes = [
        'is_active' => true,
    ];

    /**
     * Relasi: anggota milik satu organisasi.
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(OrganizationModel::class, 'organization_id');
    }

    /**
     * Relasi: anggota terkait satu user (akun sistem).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi: anggota menahan satu role/jabatan.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(RoleModel::class, 'role_id');
    }

    /**
     * Scope: hanya anggota yang aktif.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
