<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="News Aggregator API",
 *      description="API documentation for the News Aggregator",
 * )
 */
class AuthController extends Controller
{
    /**
    * @OA\Post(
    *     path="/api/register",
    *     summary="Register a new user",
    *     tags={"Authentication"},
    *     @OA\RequestBody(
    *         required=true,
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 required={"name", "email", "password"},
    *                 @OA\Property(property="name", type="string", example="Shivangi"),
    *                 @OA\Property(property="email", type="string", example="shivangi@example.com"),
    *                 @OA\Property(property="password", type="string", format="password", example="password123")
    *             )
    *         )
    *     ),
    *     @OA\Response(response=201, description="User registered successfully"),
    *     @OA\Response(response=400, description="Invalid request")
    * )
    */

    public function register(RegisterRequest $request)
    {
        try {
            $data = $request->validated();
            $data['password'] = Hash::make($data['password']);
            $user = User::create($data);
            $token = $user->createToken('API Token')->plainTextToken;

            return response()->json([
                'success' => true,
                'token' => $token,
                'user' => $user,
                'message' => 'User Registration successful!',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Registration failed!',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
    * @OA\Post(
    *     path="/api/login",
    *     summary="User Login",
    *     tags={"Authentication"},
    *     @OA\RequestBody(
    *         required=true,
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 required={"email", "password"},
    *                 @OA\Property(property="email", type="string", example="shivangi@example.com"),
    *                 @OA\Property(property="password", type="string", format="password", example="password123")
    *             )
    *         )
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="User logged in successfully",
    *         @OA\JsonContent(
    *             @OA\Property(property="success", type="boolean", example=true),
    *             @OA\Property(property="token", type="string", example="1|abc123xyz456"),
    *             @OA\Property(property="user", type="object",
    *                 @OA\Property(property="id", type="integer", example=1),
    *                 @OA\Property(property="name", type="string", example="Shivangi"),
    *                 @OA\Property(property="email", type="string", example="shivangi@example.com")
    *             )
    *         )
    *     ),
    *     @OA\Response(response=401, description="Invalid credentials"),
    *     @OA\Response(response=400, description="Invalid request")
    * )
    */

    public function login(LoginRequest $request)
    {
        try {
            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid credentials. Please check your email or password.'
                ], 401);
            }
            return response()->json([
                'success' => true,
                'token' => $user->createToken('API Token')->plainTextToken,
                'user' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Login credential is wrong!',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }
}
