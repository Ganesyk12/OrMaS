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
        'permission',
        'is_allowed',
    ];

    protected $casts = [
        'is_allowed' => 'boolean',
    ];

    protected $attributes = [
        'is_allowed' => true,
    ];

    /**
     * Relasi: hak akses milik satu user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Cek apakah user memiliki izin pada module dan permission tertentu.
     */
    public static function checkAllowed(User $user, string $module, string $permission): bool
    {
        return self::where('user_id', $user->id)
            ->where('module', $module)
            ->where('permission', $permission)
            ->where('is_allowed', true)
            ->exists();
    }
}
