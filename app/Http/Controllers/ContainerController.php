<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Container;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;

class ContainerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return container::all();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $validated = $request->validate([
            'name' => 'required',
            'type' => 'required',
        ]);
        return Container::create($validated);
    }

    /**
     * Display the specified resource.
     */
    public function show(Container $container)
    {
          return $container;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name' => 'required',
            'type' => 'required',
        ]);

        $container = Container::find($id);

        if (!$container) {
            return response()->json([
                'message' => 'Container not found'
            ], 404);
        }

        $container->update($validated);

        return response()->json([
            'message' => 'Container updated successfully',
            'container' => $container
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
   public function destroy(string $id)
    {
        $container = Container::find($id);

        if (!$container) {
            return response()->json([
                'message' => 'Container not found or already deleted'
            ], 404);
        }

        $container->delete();

        return response()->json([
            'message' => 'Container deleted successfully',
            'container' => $container
        ]);
    }

}
