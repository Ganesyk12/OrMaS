<?php

namespace Modules\Finance\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Database\Factories\FinanceFactory;
use Modules\Organization\Models\OrganizationModel;
use Modules\Activity\Models\ActivityModel;

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
        'category',
        'amount',
        'description',
        'transaction_date',
        'receipt',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
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
