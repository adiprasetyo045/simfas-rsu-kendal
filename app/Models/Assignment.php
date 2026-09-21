<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Assignment extends Model
{
    protected $fillable = [
        'report_id',
        'technician_id',
        'assigned_by',
        'assigned_at',
        'estimated_start',
        'estimated_completion',
        'actual_start',
        'actual_completion',
        'status',
        'admin_notes',
        'is_on_time',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'estimated_start' => 'datetime',
        'estimated_completion' => 'datetime',
        'actual_start' => 'datetime',
        'actual_completion' => 'datetime',
        'is_on_time' => 'boolean',
    ];

    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class);
    }

    public function technician(): BelongsTo
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    public function assignedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function progressHistories(): HasMany
    {
        return $this->hasMany(ProgressHistory::class)->orderBy('created_at', 'desc');
    }

    /**
     * Get latest progress percentage
     */
    public function getLatestProgressAttribute(): int
    {
        return $this->progressHistories()->max('progress_percentage') ?? 0;
    }

    /**
     * Determine if assignment is overdue
     */
    public function getIsOverdueAttribute(): bool
    {
        return $this->estimated_completion
            && $this->estimated_completion < now()
            && !$this->actual_completion;
    }

    /**
     * Calculate on-time status when completing
     */
    public function calculateOnTime(): void
    {
        if ($this->actual_completion && $this->estimated_completion) {
            $this->is_on_time = $this->actual_completion <= $this->estimated_completion;
            $this->save();
        }
    }
}
