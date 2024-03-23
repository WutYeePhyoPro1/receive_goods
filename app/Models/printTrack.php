<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class printTrack extends Model
{
    use HasFactory;

    protected $fillable = ['product_id','by_user','quantity','bar_type'];
}
