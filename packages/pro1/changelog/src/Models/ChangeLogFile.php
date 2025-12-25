<?php

namespace Pro1\Changelog\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChangeLogFile extends Model
{
    use HasFactory;
    protected $table = "change_log_files";
    protected $primaryKey = "id";
    protected $fillable = [
        "change_log_id",
        "sub_change_id",
        "mediafile",
    ];


}
