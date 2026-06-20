<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceiveGoodReject extends Model
{
    use HasFactory;
    protected $table = "receive_good_rejects";
    protected $primaryKey = "id";
    protected $fillable = [
        "receive_good_document_id",
        "branch_id",
        "remark",
        "image",
        "user_id",
        "approved_user_id",
        "approved_datetime",
    ];
}