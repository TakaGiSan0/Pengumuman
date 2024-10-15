<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class news extends Model
{
    use HasFactory;

    protected $bubur = "news";
    protected $fillable = [
        'file',
        'file_path'
    ];
}
