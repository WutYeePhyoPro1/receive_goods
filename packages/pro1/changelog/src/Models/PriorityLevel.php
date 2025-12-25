<?php

namespace Pro1\Changelog\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriorityLevel extends Model
{
    use HasFactory;
    protected $table = "priority_levels";
    protected $primaryKey = "id";
    protected $fillable = [
        "name",
        "slug",
        "status_id",
        "user_id"
    ];
}
