<?

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DetailedOrderResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'restaurant_id' => $this->restaurant_id,
            'created_by_user_id' => $this->created_by_user_id,
            'driver_id' => $this->driver_id,
            'status' => $this->status,
            'containers' => $this->containers->map(function ($container) {
                return [
                    'id' => $container->id,
                    'name' => $container->name,
                    'type' => $container->type,
                    'status' => $container->status,
                    'quantity' => $container->pivot->quantity,
                    'pivot_created_at' => $container->pivot->created_at,
                    'pivot_updated_at' => $container->pivot->updated_at,
                ];
            }),
        ];
    }
}
