<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgressImage extends Model
{
    protected $fillable = [
        'progress_history_id',
        'image_path',
        'type',
        'caption',
    ];

    public function progressHistory(): BelongsTo
    {
        return $this->belongsTo(ProgressHistory::class);
    }

    public function getImageUrlAttribute(): string
    {
        return asset('storage/' . $this->image_path);
    }
}
