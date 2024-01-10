<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverInfo extends Model
{
    use HasFactory;

    protected $fillable = ['ph_no','type_truck','received_goods_id','dirver_name','truck_no','nrc_no'];
}
