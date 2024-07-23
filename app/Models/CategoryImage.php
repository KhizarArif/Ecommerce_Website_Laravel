<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CategoryImage extends Model
{
    use HasFactory;
      protected $table = 'category_images';

    protected $guarded = [];
    public function product():BelongsTo{
      return $this->belongsTo(Category::class);
  }
}
