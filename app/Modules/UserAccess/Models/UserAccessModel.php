<?php

namespace Modules\UserAccess\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Database\Factories\UserAccessFactory;
use App\Models\User;

class UserAccessModel extends Model
{
    use HasFactory, SoftDeletes;

    protected static function newFactory()
    {
        return new UserAccessFactory;
    }
    protected $table = 'user_accesses';

    protected $fillable = [
        'user_id',
        'module',
        'allow_create',
        'allow_edit',
        'allow_view',
        'allow_delete',
    ];

    protected $casts = [
        'allow_create' => 'boolean',
        'allow_edit' => 'boolean',
        'allow_view' => 'boolean',
        'allow_delete' => 'boolean',
    ];

    protected $attributes = [
        'allow_create' => false,
        'allow_edit' => false,
        'allow_view' => false,
        'allow_delete' => false,
    ];

    /**
     * Relasi: hak akses milik satu user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Daftar permission yang diizinkan: view, create, edit, delete.
     *
     * @return array<int, string>
     */
    public function allowedPermissions(): array
    {
        $allowed = [];

        foreach (['view', 'create', 'edit', 'delete'] as $permission) {
            if ($this->{'allow_'.$permission}) {
                $allowed[] = $permission;
            }
        }

        return $allowed;
    }

    /**
     * Apakah user punya minimal satu permission pada modul ini.
     */
    public function hasAnyPermission(): bool
    {
        return $this->allowedPermissions() !== [];
    }

    /**
     * Cek apakah user memiliki izin pada module dan permission tertentu.
     */
    public static function checkAllowed(User $user, string $module, string $action): bool
    {
        $column = match(strtolower($action)) {
            'create' => 'allow_create',
            'edit' => 'allow_edit',
            'view' => 'allow_view',
            'delete' => 'allow_delete',
            default => null,
        };
        if (!$column) {
            return false;
        }
        return self::where('user_id', $user->id)
            ->where('module', $module)
            ->where($column, true)
            ->exists();
    }
}
