<?php

namespace Modules\Meeting\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Database\Factories\MeetingFactory;
use Modules\Organization\Models\OrganizationModel;
use Modules\Agenda\Models\AgendaModel;

class MeetingModel extends Model
{
    use HasFactory, SoftDeletes;

    protected static function newFactory()
    {
        return new MeetingFactory;
    }
    protected $table = 'meetings';

    protected $fillable = [
        'organization_id',
        'title',
        'description',
        'meeting_date',
        'location',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'meeting_date' => 'datetime',
    ];

    protected $attributes = [
        'status' => 'scheduled',
    ];

    const STATUSES = ['scheduled', 'completed', 'cancelled'];

    /**
     * Relasi: rapat milik satu organisasi.
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(OrganizationModel::class, 'organization_id');
    }

    /**
     * Relasi: satu rapat memiliki banyak agenda rapat.
     */
    public function agendas(): HasMany
    {
        return $this->hasMany(AgendaModel::class, 'meeting_id');
    }

    /**
     * Scope: filter berdasarkan status.
     */
    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }
}
