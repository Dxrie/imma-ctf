<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;
use Exception;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator()->make($request->all(), [
            'name' => 'string|min:3|max:255|required|unique:users',
            'email' => 'email|required|unique:users',
            'password' => 'string|min:8|required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'error' => $validator->errors(),
            ], 400);
        }

        try {
            User::create($request->all());

            return response()->json([
                'status' => true,
                'message' => 'User has already been registered, please login again.',
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'error' => 'User registration failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function login(Request $request)
    {
        $validator = Validator()->make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'error' => $validator->errors(),
            ], 400);
        }

        $userExists = Auth::attempt($request->only('email', 'password'));

        if (!$userExists) {
            return response()->json([
                'status' => false,
                'error' => 'Invalid credentials.',
            ], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'Logged in successfully.',
            'token' => $token,
            'user' => new UserResource($user),
        ], 200);
    }
}
