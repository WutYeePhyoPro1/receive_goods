<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverInfo extends Model
{
    use HasFactory;

    protected $fillable = ['ph_no','type_truck','received_goods_id','dirver_name','scanned_goods','truck_no','nrc_no','start_date','start_time','duration','user_id','gate','car_scanning'];

    public function truck()
    {
        return $this->belongsTo(Truck::class,'type_truck','id');
    }

    public function received()
    {
        return $this->belongsTo(GoodsReceive::class,'received_goods_id','id');
    }

    public function gates()
    {
        return $this->belongsTo(CarGate::class,'gate','id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id')->withDefault();
    }

    public function track()
    {
        return $this->hasMany(Tracking::class,'driver_info_id','id');
    }
}
