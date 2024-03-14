<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddProductTrack extends Model
{
    use HasFactory;

    protected $fillable = ['authorize_user','by_user','truck_id','product_id','added_qty'];
}
