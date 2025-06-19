<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    protected $fillable = ['name', 'address', 'phone','email', 'contact_person', 'low_stock_threshold'];

    public function managers()
    {
        return $this->belongsToMany(User::class, 'manager_restaurant')->withTimestamps();
    }
    public function containerInventory()
    {
        return $this->hasMany(RestaurantContainerInventory::class);
    }
}

