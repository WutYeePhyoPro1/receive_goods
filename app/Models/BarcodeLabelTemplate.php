<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarcodeLabelTemplate extends Model
{
    protected $fillable = [
        'name',
        'layout',
    ];

    protected $casts = [
        'layout' => 'array',
    ];
}
