<?php

namespace Pro1\Changelog\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubChange extends Model
{
    use HasFactory;
    protected $table = "sub_changes";
    protected $primaryKey = "id";
    protected $fillable = [
        "change_log_id",
        "title",
        "description",
        "change_type_id",
        "user_id",
        "assignee_id"
    ];

    public function changetype(){
        return $this->belongsTo(ChangeType::class,'change_type_id','id');
    }

    public function changelogfiles(){
        return $this->hasMany(ChangeLogFile::class,'sub_change_id','id');
    }
}
