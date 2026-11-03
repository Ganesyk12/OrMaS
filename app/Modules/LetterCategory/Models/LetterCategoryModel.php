<?php

namespace Modules\LetterCategory\Models;

use Database\Factories\LetterCategoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Letter\Models\LetterModel;

class LetterCategoryModel extends Model
{
    use HasFactory, SoftDeletes;

    protected static function newFactory()
    {
        return new LetterCategoryFactory;
    }

    protected $table = 'letter_categories';

    protected $fillable = [
        'code',
        'name',
        'description',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $attributes = [
        'is_active' => true,
    ];

    /**
     * Relasi: satu kategori memiliki banyak surat.
     */
    public function letters(): HasMany
    {
        return $this->hasMany(LetterModel::class, 'category_id');
    }

    /**
     * Scope: hanya kategori yang aktif.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
