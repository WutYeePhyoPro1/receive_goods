<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class R008DocumentFile extends Model
{
    use HasFactory;
    protected $table = "r008_document_files";
    protected $primaryKey = "id";
    protected $fillable = [
        "r008_document_id",
        "file",
        "name",
        "data",
    ];
}
