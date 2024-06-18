<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Exhibition extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $table = 'exhibitions';
    
    public function exhibitionImages():HasMany{
        return $this->hasMany(ExhibitionImage::class);
    }
}
