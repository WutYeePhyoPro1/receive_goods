<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceiveGoodReject extends Model
{
    use HasFactory;
    protected $table = "receive_good_rejects";
    protected $primaryKey = "id";
    protected $fillable = [
        "receive_good_document_id",
        "branch_id",
        "remark",
        "image",
        "user_id",
        "approved_user_id",
        "approved_datetime",
        "status"
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    
    public function branch(){
        return $this->belongsTo(Branch::class);
    }


    public function approved_user()
    {
        return $this->belongsTo(User::class, 'approved_user_id', 'id');
    }

    public function receive_good_document(){
        return $this->belongsTo(ReceiveGoodDocument::class,'receive_good_document_id','id');
    }
}
