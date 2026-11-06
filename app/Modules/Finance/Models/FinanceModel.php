<?php

namespace Modules\Finance\Models;

use Database\Factories\FinanceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Activity\Models\ActivityModel;
use Modules\Organization\Models\OrganizationModel;

class FinanceModel extends Model
{
    use HasFactory, SoftDeletes;

    protected static function newFactory()
    {
        return new FinanceFactory;
    }

    protected $table = 'finances';

    protected $fillable = [
        'organization_id',
        'activity_id',
        'type',
        'transaction_date',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'transaction_date' => 'date',
    ];

    protected $attributes = [
        'status' => 'draft',
    ];

    const TYPES = ['income', 'expense'];

    const STATUSES = ['draft', 'approved', 'rejected'];

    /**
     * Relasi: transaksi keuangan milik satu organisasi.
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(OrganizationModel::class, 'organization_id');
    }

    /**
     * Relasi: transaksi terkait satu kegiatan (opsional).
     */
    public function activity(): BelongsTo
    {
        return $this->belongsTo(ActivityModel::class, 'activity_id');
    }

    /**
     * Relasi: satu transaksi memiliki banyak detail (kategori, nominal, bukti).
     */
    public function details(): HasMany
    {
        return $this->hasMany(FinanceDetailModel::class, 'finance_id');
    }

    /**
     * Total nominal transaksi = akumulasi seluruh detail.
     */
    public function getTotalAttribute(): float
    {
        if (array_key_exists('details_sum_amount', $this->attributes)) {
            return (float) ($this->attributes['details_sum_amount'] ?? 0);
        }

        $total = $this->relationLoaded('details')
            ? $this->details->sum('amount')
            : $this->details()->sum('amount');

        return (float) $total;
    }

    /**
     * Scope: filter berdasarkan tipe (pemasukan/pengeluaran).
     */
    public function scopeType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope: filter berdasarkan status.
     */
    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }
}
