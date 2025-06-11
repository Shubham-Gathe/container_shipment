<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'restaurant_id',
        'created_by_user_id',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];


   public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
   public function containers()
    {
        return $this->belongsToMany(Container::class, 'delivery_containers')
                    ->withPivot('quantity', 'created_at', 'updated_at') // include your pivot fields
                    ->withTimestamps(); // optional if you want timestamp info
    }
}
