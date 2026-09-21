<?php

namespace App\Models;

use App\Enums\ReportPriority;
use App\Enums\ReportStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Report extends Model
{
    protected $fillable = [
        'ticket_number',
        'user_id',
        'room_id',
        'category_id',
        'facility_id',
        'priority',
        'status',
        'location_detail',
        'description',
        'reported_at',
    ];

    protected $casts = [
        'status' => ReportStatus::class,
        'priority' => ReportPriority::class,
        'reported_at' => 'datetime',
    ];

    // ─── Relationships ─────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function facility(): BelongsTo
    {
        return $this->belongsTo(Facility::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ReportImage::class);
    }

    public function assignment(): HasOne
    {
        return $this->hasOne(Assignment::class)->latestOfMany();
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class);
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(StatusHistory::class)->orderBy('created_at', 'desc');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    // ─── Scopes ────────────────────────────────────────────

    public function scopeByStatus($query, ReportStatus $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByPriority($query, ReportPriority $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', array_map(
            fn($s) => $s->value,
            ReportStatus::activeStatuses()
        ));
    }

    public function scopeToday($query)
    {
        return $query->whereDate('reported_at', today());
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('reported_at', now()->month)
                     ->whereYear('reported_at', now()->year);
    }

    public function scopeOverdue($query)
    {
        return $query->whereHas('assignment', function ($q) {
            $q->whereNotNull('estimated_completion')
              ->where('estimated_completion', '<', now())
              ->whereNull('actual_completion');
        })->active();
    }

    // ─── Helpers ───────────────────────────────────────────

    /**
     * Generate unique ticket number: TKT-YYYYMMDD-XXXX
     */
    public static function generateTicketNumber(): string
    {
        $date = now()->format('Ymd');
        $prefix = "TKT-{$date}-";

        $lastTicket = static::where('ticket_number', 'like', "{$prefix}%")
            ->orderBy('ticket_number', 'desc')
            ->first();

        if ($lastTicket) {
            $lastNumber = (int) substr($lastTicket->ticket_number, -4);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return $prefix . $newNumber;
    }

    /**
     * Check if the report is overdue based on assignment estimate
     */
    public function getIsOverdueAttribute(): bool
    {
        if (!$this->assignment) {
            return false;
        }

        return $this->assignment->estimated_completion
            && $this->assignment->estimated_completion < now()
            && !$this->assignment->actual_completion
            && in_array($this->status, ReportStatus::activeStatuses());
    }

    /**
     * Check if report can be edited (only before verification)
     */
    public function canBeEdited(): bool
    {
        return $this->status === ReportStatus::MENUNGGU_VERIFIKASI;
    }
}
