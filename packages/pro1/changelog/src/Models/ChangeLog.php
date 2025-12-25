<?php

namespace Pro1\Changelog\Models;

use Pro1\Changelog\Models\BaseRole as Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChangeLog extends Model
{
    use HasFactory;
    protected $table = "change_logs";
    protected $primaryKey = "id";
    protected $fillable = [
        "title",
        "description",
        "release_type_id",
        "version_number",
        "release_date",
        "priority_level_id",
        "user_id",
        "status_id",
        "requester_id",
    ];

    public function subchanges(){
        return $this->hasMany(SubChange::class,'change_log_id','id');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function releasetype(){
        return $this->belongsTo(ReleaseType::class,'release_type_id','id');
    }

    public function changelogfiles(){
        return $this->hasMany(ChangeLogFile::class,'change_log_id','id');
    }

    public function roles(){
        return $this->belongsToMany(Role::class,"change_log_roles","change_log_id","role_id");
    }

    public function isRead(){
        $user = Auth::user();
        $user_id = $user["id"];

        $userchangelogread = UserChangelogRead::
        where('change_log_id',$this->id)
        ->where('user_id',$user_id)
        ->first();

        if($userchangelogread){
            return true;
        }
        return false;
    }


    public function sendWhatsNews($users){
        foreach($users as $user){
            $whatsnew = WhatsNew::updateOrCreate(
                [
                "change_log_id" =>$this->id,
                "user_id" => $user->id
                ],
                [ "read_at" => null]
            );
        }
    }

    public function isAgreed(){
        $user = Auth::user();
        if($user){
            $whatsnew = WhatsNew::where("user_id",$user->id)
            ->where("change_log_id",$this->id)->first();

            return $whatsnew ? $whatsnew->read_at : false;
        }
        return false;

    }
}
