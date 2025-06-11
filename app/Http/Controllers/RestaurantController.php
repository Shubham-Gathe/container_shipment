<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Restaurant;
use App\Models\User;

class RestaurantController extends Controller
{
    public function index()
    {
        $restaurants = Restaurant::all(); // or with pagination
        return response()->json($restaurants);
    }
   public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email|max:255',
            'contact_person' => 'nullable|string|max:255',
            'low_stock_threshold' => 'nullable|integer|min:0',
        ]);

        $restaurant = Restaurant::create($validated);

        return response()->json([
            'message' => 'Restaurant created successfully.',
            'data' => $restaurant,
        ], 201);
    }
    public function update(Request $request, $id)
    {
        $restaurant = Restaurant::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email|max:255',
            'contact_person' => 'nullable|string|max:255',
            'low_stock_threshold' => 'nullable|integer|min:0',
        ]);

        $restaurant->update($validated);

        return response()->json([
            'message' => 'Restaurant updated successfully.',
            'data' => $restaurant,
        ]);
    }
    public function destroy($id)
    {
        $restaurant = Restaurant::findOrFail($id);
        $restaurant->delete();
        return response()->json(['message' => 'Restaurant deleted successfully.']);  
    }

    public function assignRestaurantToManager(Request $request)
    {
        $validated = $request->validate([
            'restaurant_id' => 'required|exists:restaurants,id',
            'manager_ids' => 'required|exists:users,id',
        ]);

        // Fetch only users who have 'manager' role from the provided IDs
        $validManagerIds = User::whereIn('id', $validated['manager_ids'])
            ->where('role', 'manager')
            ->pluck('id')
            ->toArray();

        // Sync only valid manager IDs
        $restaurant = Restaurant::findOrFail($validated['restaurant_id']);
        $restaurant->managers()->sync($validManagerIds);


        $allIds = $validated['manager_ids'];
        $skippedIds = array_diff($allIds, $validManagerIds);

        return response()->json([
            'message' => 'Managers assigned successfully.',
            'assigned_manager_ids' => $validManagerIds,
            'skipped_non_managers' => array_values($skippedIds),
        ]);
    }
}   
