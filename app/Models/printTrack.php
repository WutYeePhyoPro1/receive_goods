<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class printTrack extends Model
{
    use HasFactory;

    protected $fillable = ['product_id','by_user','quantity','bar_type','reason'];

    public function product()
    {
        return $this->belongsTo(Product::class,'product_id')->withDefault();
    }

    public function reasons()
    {
        return $this->belongsTo(PrintReason::class,'reason')->withDefault();
    }
}
