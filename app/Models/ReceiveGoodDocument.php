<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceiveGoodDocument extends Model
{
    use HasFactory;
    protected $table = "receive_good_documents";
    protected $primaryKey = "id";
    protected $fillable = [
        "document_id",
        "vendor_code",
        "po_no",
        "branch_id",
        "delivery_note",
        "delivery_date",
        "ship_by",
        "receive_type",
        "r008",
        "total_amount",
        "rg_no",
        "r008_no",
    ];
}
