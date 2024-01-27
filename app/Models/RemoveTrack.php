<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RemoveTrack extends Model
{
    use HasFactory;

    protected $fillable = ['received_goods_id','user_id','product_id','remove_qty'];

    public function received()
    {
        return $this->belongsTo(GoodsReceive::class,'received_goods_id','id');
    }

    public function product()
    {
        return $this->hasOne(Product::class,'id','product_id');
    }

    public function user()
    {
        return $this->hasOne(User::class,'id','user_id');
    }
}
