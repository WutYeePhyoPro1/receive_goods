<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'document_id',
        'bar_code',
        'supplier_name',
        'qty',
        'scanned_qty',
        'remark',
        'unit',
        'scann_count',
        'price',
        'amount',
        'rg_pulled_qty',
    ];

    public function document(){
        return $this->belongsTo(Document::class, 'document_id', 'id');
    }

}
