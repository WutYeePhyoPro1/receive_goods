<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GoodsReceive extends Model
{
    use HasFactory;

    protected $fillable = ['document_no','start_date','start_time','vendor_name','status','user_id','branch_id','source','total_duration','remaining_qty','exceed_qty'];

    public function car_info(){
        return $this->hasMany(DriverInfo::class,'id', 'received_goods_id');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function source_good(){
        return $this->belongsTo(Source::class,'source','id');
    }

    // public function documents()
    // {
    //     return $this->hasMany(Document::class, 'received_goods_id');
    // }


}
