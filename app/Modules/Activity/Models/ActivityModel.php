<?php

namespace Modules\Activity\Models;

use Database\Factories\ActivityFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Finance\Models\FinanceModel;
use Modules\Organization\Models\OrganizationModel;
use Modules\Shared\Concerns\BelongsToVisibleOrganization;

class ActivityModel extends Model
{
    use BelongsToVisibleOrganization, HasFactory, SoftDeletes;

    protected static function newFactory()
    {
        return new ActivityFactory;
    }

    protected $table = 'activities';

    protected $fillable = [
        'organization_id',
        'title',
        'description',
        'type',
        'start_date',
        'end_date',
        'location',
        'budget',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'budget' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    protected $attributes = [
        'status' => 'planning',
    ];

    const TYPES = ['program', 'kegiatan', 'pelatihan', 'sosial'];

    const STATUSES = ['planning', 'ongoing', 'completed', 'cancelled'];

    /**
     * Relasi: kegiatan milik satu organisasi.
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(OrganizationModel::class, 'organization_id');
    }

    /**
     * Relasi: satu kegiatan dapat memiliki banyak transaksi keuangan.
     */
    public function finances(): HasMany
    {
        return $this->hasMany(FinanceModel::class, 'activity_id');
    }

    /**
     * Scope: filter berdasarkan status.
     */
    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }
}
