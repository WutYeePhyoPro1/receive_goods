<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;
    protected $fillable = ['document_no','received_goods_id','remark','outbound'];

    public function received()
    {
        return $this->belongsTo(GoodsReceive::class,'received_goods_id','id');
    }
}
