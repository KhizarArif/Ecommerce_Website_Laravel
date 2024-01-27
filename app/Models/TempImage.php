<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempImage extends Model
{
    protected $table = 'temp_images';
    
    use HasFactory;
    protected $guarded = [];
}
