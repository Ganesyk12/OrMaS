<?php

namespace Modules\Finance\Models;

use Database\Factories\FinanceDetailFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class FinanceDetailModel extends Model
{
    use HasFactory, SoftDeletes;

    protected static function newFactory()
    {
        return new FinanceDetailFactory;
    }

    protected $table = 'finance_details';

    protected $fillable = [
        'finance_id',
        'category',
        'amount',
        'receipt',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    /**
     * Relasi: detail milik satu transaksi keuangan.
     */
    public function finance(): BelongsTo
    {
        return $this->belongsTo(FinanceModel::class, 'finance_id');
    }
}
