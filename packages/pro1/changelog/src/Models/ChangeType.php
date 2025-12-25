<?php

namespace Pro1\Changelog\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChangeType extends Model
{
    use HasFactory;
    protected $table = "change_types";
    protected $primaryKey = "id";
    protected $fillable = [
        "name",
        "slug",
        "status_id",
        "user_id"
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
