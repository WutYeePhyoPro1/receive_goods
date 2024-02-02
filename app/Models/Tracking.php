<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tracking extends Model
{
    use HasFactory;

    protected $fillable = ['driver_info_id','product_id','scanned_qty','user_id'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id')->withDefault();
    }

    public function truck()
    {
        return $this->belongsTo(DriverInfo::class,'driver_info_id')->withDefault();
    }
}
