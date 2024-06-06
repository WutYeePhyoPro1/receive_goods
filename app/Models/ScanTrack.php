<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScanTrack extends Model
{
    use HasFactory;

    protected $fillable = ['driver_info_id','product_id','user_id','Unit','per','count'];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class,'product_id','id');
    }

    public function driver()
    {
        return $this->belongsTo(DriverInfo::class,'driver_info_id','id');
    }
}
