<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReceiveGoodFile extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $table = "receive_good_files";
    protected $primaryKey = "id";
    protected $fillable = [
        "receive_good_document_id",
        "file",
        "name",
        "data",
    ];
}
