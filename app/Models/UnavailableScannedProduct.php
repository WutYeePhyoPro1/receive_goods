<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnavailableScannedProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'received_goods_id',
        'ip_address',
        'scanned_barcode',
    ];

}
