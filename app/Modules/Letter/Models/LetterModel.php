<?php

namespace Modules\Letter\Models;

use Database\Factories\LetterFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\LetterCategory\Models\LetterCategoryModel;

class LetterModel extends Model
{
    use HasFactory, SoftDeletes;

    protected static function newFactory()
    {
        return new LetterFactory;
    }

    protected $table = 'letters';

    protected $fillable = [
        'category_id',
        'direction',
        'letter_number',
        'subject',
        'sender',
        'recipient',
        'letter_date',
        'received_date',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'letter_date' => 'date',
        'received_date' => 'date',
    ];

    const DIRECTIONS = ['incoming', 'outgoing'];

    /**
     * Relasi: surat termasuk dalam satu kategori.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(LetterCategoryModel::class, 'category_id');
    }

    /**
     * Label arah surat.
     */
    public function getDirectionLabelAttribute(): string
    {
        return $this->direction === 'incoming' ? 'Surat Masuk' : 'Surat Keluar';
    }

    /**
     * Scope: filter berdasarkan arah surat.
     */
    public function scopeDirection($query, string $direction)
    {
        return $query->where('direction', $direction);
    }

    /**
     * Scope: pencarian nomor surat, perihal, pengirim, atau tujuan.
     */
    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($query) use ($search) {
            $query->where('letter_number', 'like', "%{$search}%")
                ->orWhere('subject', 'like', "%{$search}%")
                ->orWhere('sender', 'like', "%{$search}%")
                ->orWhere('recipient', 'like', "%{$search}%");
        });
    }
}
