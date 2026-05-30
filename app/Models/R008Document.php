<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class R008Document extends Model
{
    use HasFactory;
    protected $table = "r008_documents";
    protected $primaryKey = "id";
    protected $fillable = [
        "document_date",
        "product_type",
        "rg_no",
        "vendor_code",
        "truck_container_no",
        "remark",
        "branch_id",
        "user_id",
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function branch(){
        return $this->belongsTo(Branch::class);
    }

    public function r008_products()
    {
        return $this->hasMany(R008Product::class, 'r008_document_id', 'id');
    }

    public function r008_files(){
        return $this->hasMany(R008DocumentFile::class,'r008_document_id','id');
    }

    public function receive_good_document(){
        $rg_no = $this->rg_no;
        $receive_good_document = ReceiveGoodDocument::with('vendor')
        ->whereHas('receive_good_files', function ($q) use ($rg_no) {
            $q->where('file', $rg_no);
        })
        ->first();

        return $receive_good_document;
    }


    public function vendor(){
        return $this->belongsTo(Vendor::class,'vendor_code','vendor_code');
    }



}
