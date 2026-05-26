<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceiveGoodFile extends Model
{
    use HasFactory;
    protected $table = "receive_good_files";
    protected $primaryKey = "id";
    protected $fillable = [
        "receive_good_document_id",
        "file",
        "name",
        "data",
    ];
}
