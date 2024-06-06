<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarGate extends Model
{
    use HasFactory;

    public function branches()
    {
        return $this->belongsTo(Branch::class,'branch','branch_code');
    }
}
