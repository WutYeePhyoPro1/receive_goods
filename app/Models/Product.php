<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['document_id','bar_code','supplier_name','qty','scanned_qty','remark','scann_count'];

    public function doc(){
        return $this->belongsTo(Document::class, 'document_id', 'id');
    }

}
