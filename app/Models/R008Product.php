<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class R008Product extends Model
{
    use HasFactory;
    protected $table = "r008_products";
    protected $primaryKey = "id";
    protected $fillable = [
        "r008_document_id",
        "product_code",
        "product_name",
        "gr_qty",
        "physical_qty",
        "diff",
        "status_id",
    ];
}
