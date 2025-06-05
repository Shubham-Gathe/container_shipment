<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\API\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Login method
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        $user = Auth::user();

        // Create token
       $tokenResult = $user->createToken('api-token');
       $token = $tokenResult->plainTextToken;

        
        $tokenResult->accessToken->expires_at = now()->addHours(2);
        $tokenResult->accessToken->save();

        return response()->json([
            'token' => $token,
            'expires_at' => $tokenResult->accessToken->expires_at,
            'user' => $user,
        ]);
    }

     // Logout method
    public function logout(Request $request)
    {
        // Revoke current token
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out']);
    }
}
