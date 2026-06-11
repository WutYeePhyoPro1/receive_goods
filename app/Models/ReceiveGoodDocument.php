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
        "user_id",
        "status",
        "rejected_by",
        "rejected_at",
        "remark"
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function branch(){
        return $this->belongsTo(Branch::class);
    }

    public function document(){
        return $this->belongsTo(Document::class,'po_no','document_no');
    }

    public function good_receive(){
        return $this->belongsTo(GoodsReceive::class,'document_id','id');
    }

    public function receive_good_products()
    {
        return $this->hasMany(ReceiveGoodProduct::class, 'receive_good_document_id', 'id');
    }

    public function vendor(){
        return $this->belongsTo(Vendor::class,'vendor_code','vendor_code');
    }

    public function receive_good_files(){
        return $this->hasMany(ReceiveGoodFile::class,'receive_good_document_id','id');
    }

    public function rejected(){
        return $this->belongsTo(User::class,'rejected_by','id');
    } 



}
