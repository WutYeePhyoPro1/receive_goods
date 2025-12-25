<?php

namespace Pro1\Changelog\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChangeLogRole extends Model
{
    use HasFactory;

    protected $table = "change_log_roles";
    protected $primaryKey = "id";
    protected $fillable = [
        "change_log_id",
        "role_id",
    ];

}
