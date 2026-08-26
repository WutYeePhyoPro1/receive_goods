<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;
    protected $fillable = [
        'document_no',
        'received_goods_id',
        'remark',
        'outbound',
        'creditday',
        'purchasedate',
        'vendor_name',
        'vendor_code',
        'remark',
        'total_amount',
        'status',
        'branch_id',
        "rejected_by",
        "rejected_at",
        'barcode_path',
        'qr_code_path',

        'employeecode',
        'employee_name',
        'emp_comname',
        'time_emp',

        'emp_approve',
        'approve_name',
        'approve_comname',
        'time_approve',

        'check_emp',
        'check_name',
        'chekc_comname',
        'time_check',
    ];


    public function received()
    {
        return $this->belongsTo(GoodsReceive::class,'received_goods_id','id');
    }

    public function purchase_order_items()
    {
        return $this->hasMany(PurchaseOrderItem::class, 'document_id', 'id');
    }

    public function branch(){
        return $this->belongsTo(Branch::class);
    }

    public function vendor(){
        return $this->belongsTo(Vendor::class,'vendor_code','vendor_code');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'document_id', 'id');
    }


}