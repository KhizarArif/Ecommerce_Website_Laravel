<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExhibitionImage extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $table = 'exhibition_images';
    
    public function exhibition():BelongsTo{
        return $this->belongsTo(Exhibition::class);
    }
}
