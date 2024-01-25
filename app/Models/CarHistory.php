<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarHistory extends Model
{
    use HasFactory;
    protected $fillable = ['car_no','car_type','driver_name'];
}
