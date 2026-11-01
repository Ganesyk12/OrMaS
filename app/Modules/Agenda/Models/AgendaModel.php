<?php

namespace Modules\Agenda\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Database\Factories\AgendaFactory;
use Modules\Meeting\Models\MeetingModel;

class AgendaModel extends Model
{
    use HasFactory, SoftDeletes;

    protected static function newFactory()
    {
        return new AgendaFactory;
    }
    protected $table = 'agendas';

    protected $fillable = [
        'meeting_id',
        'title',
        'description',
        'presenter',
        'duration_minutes',
        'order',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'duration_minutes' => 'integer',
        'order' => 'integer',
    ];

    protected $attributes = [
        'status' => 'pending',
        'order' => 0,
    ];

    const STATUSES = ['pending', 'discussed', 'completed', 'skipped'];

    /**
     * Relasi: agenda milik satu rapat.
     */
    public function meeting(): BelongsTo
    {
        return $this->belongsTo(MeetingModel::class, 'meeting_id');
    }

    /**
     * Scope: urutkan berdasarkan urutan agenda.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc');
    }

    /**
     * Scope: filter berdasarkan status.
     */
    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }
}
