<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestaurantContainerInventory extends Model
{
    protected $table = 'restaurant_container_inventory';

    protected $fillable = [
        'restaurant_id',
        'container_id',
        'quantity',
        'low_stock_threshold',
    ];

    public function container()
        {
            return $this->belongsTo(Container::class);
        }
}
