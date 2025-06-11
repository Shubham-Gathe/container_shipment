<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'restaurant_id' => $this->restaurant_id,
            'created_by_user_id' => $this->created_by_user_id,
            'status' => $this->status,
            'containers' => $this->containers->map(function ($container) {
                return [
                    'id' => $container->id,
                    'name' => $container->name,
                    'status' => $container->status,
                    'current_quantity' => $container->current_quantity,
                    'type' => $container->type,
                    'pivot' => [
                        'order_id' => $container->pivot->order_id,
                        'container_id' => $container->pivot->container_id,
                        'quantity' => $container->pivot->quantity,
                    ],
                ];
            }),
        ];
    }
}
