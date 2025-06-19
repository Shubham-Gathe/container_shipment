<?php

namespace App\Models;
use HasFactory;

   
use Illuminate\Database\Eloquent\Model;

class Container extends Model
{

     protected $fillable = [
        'name',
        'type',
        'status',
        'current_quantity'
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'delivery_containers')
        ->withPivot('quantity')
        ->withTimestamps();
    }
}
