<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // Register
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'is_admin' => 'numeric|in:0,1',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'is_admin' => $validated['is_admin'],
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return encrypt_response(['token' => $token, 'user' => $user]);
        
    }

    // Login
    public function login(Request $request)
    {
        $validated = $request->validate(['email' => 'required|email', 'password' => 'required']);

        $user = User::where('email', $validated['email'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            throw ValidationException::withMessages(['email' => ['Invalid credentials']]);
        }

        $user->tokens()->delete();

        $token = $user->createToken('auth_token')->plainTextToken;

        $massage = [
            "title" => "success",
            "body" => "You have logged in successfully"
        ];

        // return encrypt_response(['token' => $token, 'user' => $user]);
        return response()->json(['token' => $token,'massage' =>$massage, 'user' => $user]);
    }

    // Get Auth User
    public function user(Request $request)
    {
        // return encrypt_response($request->user());
        return response()->json($request->user());
    }

    // Logout
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out']);
    }
}
