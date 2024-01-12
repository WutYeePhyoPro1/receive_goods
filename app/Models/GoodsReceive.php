<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GoodsReceive extends Model
{
    use HasFactory;

    protected $fillable = ['document_no','start_date','start_time','vendor_name','status','user_id','branch_id','edit_user','duration','edit_start_time','edit_duration','remaining_qty','exceed_qty'];

    public function car_info(){
        return $this->belongsTo(DriverInfo::class,'id', 'received_goods_id');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }

    // public function documents()
    // {
    //     return $this->hasMany(Document::class, 'received_goods_id');
    // }


}
