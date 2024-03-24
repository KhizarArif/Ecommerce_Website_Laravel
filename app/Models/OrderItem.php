<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class OrderItem extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $table = 'order_items';

    public function orders():BelongsToMany{
        return $this->belongsToMany(Order::class);
    }
}
