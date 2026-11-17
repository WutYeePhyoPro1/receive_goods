<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BarcodeLabelTemplate extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'width_mm',
        'height_mm',
        'settings',
    ];

    protected $casts = [
        'settings' => 'array',
        'width_mm' => 'decimal:2',
        'height_mm' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
