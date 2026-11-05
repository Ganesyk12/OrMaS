<?php

namespace Modules\UserAccess\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ModuleModel extends Model
{
    protected $table = 'modules';

    protected $fillable = [
        'name',
        'label',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Hanya modul yang aktif.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Query modul aktif dengan urutan tampil.
     */
    public static function activeList(): Builder
    {
        return static::query()
            ->active()
            ->orderBy('sort_order')
            ->orderBy('name');
    }

    /**
     * Nama modul aktif (dipakai sebagai key user_accesses.module).
     *
     * @return array<int, string>
     */
    public static function activeNames(): array
    {
        return static::activeList()->pluck('name')->all();
    }
}
