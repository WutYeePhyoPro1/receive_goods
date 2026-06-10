<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceiveGoodProduct extends Model
{
    use HasFactory;
    protected $table = "receive_good_products";
    protected $primaryKey = "id";
    protected $fillable = [
        "receive_good_document_id",
        "product_id",
        "product_code",
        "product_name",
        "unit",
        "po_qty",
        "gr_qty",
        "price",
        "amount",
        "remark",
        "r8damqty"
    ];
}
