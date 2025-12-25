<?php

namespace Pro1\Changelog\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WhatsNew extends Model
{
    use HasFactory;
    protected $table = "whats_news";
    protected $primaryKey = "id";
    protected $fillable = [
        "change_log_id",
        "user_id",
        "read_at"
    ];


    public static function getAuthUserWhatNews($status=0)
    {
        $user = Auth::user();
        $user_id = $user->id ?? $user->uuid;

        $whatnews = WhatsNew::where("user_id", $user_id)
        ->when($status == 'read', function ($query) {
            return $query->whereNotNull('read_at');
        }, function ($query) {
            return $query->whereNull('read_at');
        })
        ->orderBy("id", 'desc')
        ->get();


        return $whatnews;
    }

}
