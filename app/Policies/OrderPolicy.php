<?php

namespace App\Policies;

use App\Models\User;

class OrderPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }
    public function create(User $user, Restaurant $restaurant)
    {
        return $user->restaurants->contains($restaurant);
    }
}
