<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
   public function index(Request $request)
    {
        $query = User::query();

       $allowedRoles = ['admin', 'manager', 'driver'];

        if ($request->filled('role') && in_array($request->input('role'), $allowedRoles)) {
            $query->where('role', $request->input('role'));
        }

        $users = $query->get();

        if ($request->wantsJson()) {
            return response()->json($users);
        }

        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

   public function store(Request $request)
    {
        // Step 1: Validate common user fields first
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:8',
            'role' => 'required|in:admin,caller,manager,driver',
        ]);

        // Step 2: If role is driver, validate driver fields BEFORE creating user
        if ($validated['role'] === 'driver') {
            $driverValidated = $request->validate([
                'license_plate' => 'required|string',
                'vehicle_type' => 'nullable|string',
                'name' => 'required|string',
                'phone' => 'required|string',
            ]);
        }

        // Step 3: Now proceed with creating user
        $validated['password'] = Hash::make($validated['password']);
        $user = User::create($validated);

        // Step 4: If driver, create related driver record
        if ($validated['role'] === 'driver') {
            $driverValidated['status'] = 'pending';
            $user->driver()->create($driverValidated);
        }

        // Step 5: Response
        if ($request->wantsJson()) {
            return response()->json($user->load('driver'), 201);
        }

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }


    public function show(Request $request, User $user)
    {
        if ($request->wantsJson()) {
            return response()->json($user);
        }

        return view('users.show', compact('user')); // Optional
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|confirmed|min:8',
            'role' => 'required|in:admin,caller,executive,driver',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        DB::transaction(function () use ($user, $validated, $request) {
            $user->update($validated);

            if ($validated['role'] === 'driver') {
                $driverValidated = $request->validate([
                    'license_number' => 'required|string',
                    'vehicle_type' => 'nullable|string',
                ]);

                if ($user->driver) {
                    $user->driver->update($driverValidated);
                } else {
                    $user->driver()->create($driverValidated);
                }
            } else {
                $user->driver()?->delete();
            }
        });

        if ($request->wantsJson()) {
            return response()->json($user->load('driver'));
        }

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }


    public function destroy(Request $request, User $user)
    {
        $user->delete();

        if ($request->wantsJson()) {
            return response()->json(['message' => 'User deleted successfully.']);
        }

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
