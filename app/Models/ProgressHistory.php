<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProgressHistory extends Model
{
    protected $fillable = [
        'assignment_id',
        'technician_id',
        'status',
        'description',
        'progress_percentage',
    ];

    protected $casts = [
        'progress_percentage' => 'integer',
    ];

    public function assignment(): BelongsTo
    {
        return $this->belongsTo(Assignment::class);
    }

    public function technician(): BelongsTo
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProgressImage::class);
    }
}
