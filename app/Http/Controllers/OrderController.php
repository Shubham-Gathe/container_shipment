<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderResource;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;

class OrderController extends Controller
{
    public function index()
    {
       $user = auth()->user();

        // Base query
        $query = Order::with('containers');

        // Role-based filtering
        if ($user->role === 'admin') {
            // Admin sees all orders — no changes
        } elseif ($user->role === 'manager') {
            $restaurantIds = $user->managedRestaurants->pluck('id');
            $query->whereIn('restaurant_id', $restaurantIds)->where('created_by_user_id', $user->id);;   
            } elseif ($user->role === 'driver') {
            $driver = $user->driver->id ?? null; 
            if (!$driver) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
            $query->where('driver_id', $driver); 
        } else {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $orders = $query->get();

        return OrderResource::collection($orders);
    }

    public function show($id)
    {
        // Logic to retrieve a specific order by ID
        return view('orders.show', ['order' => $id]);
    }

    public function create()
    {
        // Logic to show the form for creating a new order
        return view('orders.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'restaurant_id' => 'required|exists:restaurants,id',
            'containers' => 'required|array',
            'containers.*.id' => 'required|exists:containers,id',
            'containers.*.quantity' => 'required|integer|min:1',
        ]);
        
        // Ensure user is linked to restaurant (authorization)
        $restaurant = auth()->user()->restaurants()->findOrFail($request->restaurant_id);

        if (!$restaurant) {
            return response()->json(['error' => 'Unauthorized user.'], 403);
        }
        // Create order
        $order = Order::create([
            'restaurant_id' => $restaurant->id,
            'created_by_user_id' => auth()->id(),
            'status' => 'pending',
        ]);

        // Attach containers with quantity
        foreach ($request->containers as $containerData) {
            $order->containers()->attach($containerData['id'], [
                'quantity' => $containerData['quantity'],
            ]);
        }
        return new OrderResource($order->load('containers'));
    }

    public function assignDriver(Request $request, $orderId)
    {
        $request->validate([
            'driver_id' => 'required|exists:drivers,id',
        ]);

        // Fetch the order
        $order = Order::where('id', $orderId)->firstOrFail();

        if (!is_null($order->driver_id)) {
            return response()->json([
                'error' => 'This order is already assigned to a driver.'
            ], 400);
        }

        $order->driver_id = $request->driver_id;
        $order->save();

        return response()->json([
            'message' => 'Driver assigned successfully.',
            'order' => $order
        ]);
    }

    public function respondToOrder(Request $request, Order $order)
    {
        $request->validate([
            'response' => 'required|in:accepted,rejected',
        ]);
        $driver = auth()->user()->driver;   
      
        // Make sure the authenticated driver owns this assignment
        if ($order->driver_id !== $driver->id) {
            return response()->json([
                'error' => 'You are not authorized to respond to this order.'
            ], 403);
        }
        // Optional: Check if already responded
        if ($order->driver_response !== 'pending') {
            return response()->json([
                'error' => 'You have already responded to this order.'
            ], 400);
        }

        // Update response
        $order->driver_response = $request->response;
        $order->save();

        return response()->json([
            'message' => "Order has been {$request->response} by the driver.",
            'order' => $order
        ]);
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        $user = $request->user();
        // Only allow drivers to update status of their own assigned orders
        if ($user->role !== 'driver') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $driver = auth()->user()->driver;  
        // Optional: Check if driver is assigned to this order
        if ($order->driver_id !== $driver->id) {
            return response()->json(['message' => 'This order is not assigned to you.'], 403);
        }

        // Validate the new status
        $validated = $request->validate([
            'status' => 'required|string|in:pending,processing,shipped,delivered,cancelled',
        ]);

        $order->status = $validated['status'];
        $order->save();

        return response()->json(['message' => 'Order status updated successfully.']);
    }
}