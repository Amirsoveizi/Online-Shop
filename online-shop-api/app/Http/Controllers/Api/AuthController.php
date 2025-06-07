<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Models\User;
use App\Role;
use App\Http\Resources\UserResource;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
      /**
     * Register a new user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => ['required', 'confirmed', Password::defaults()],
            'role' => ['required', Rule::in(Role::values())],
        ]);

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => $validatedData['password'],
            'role' => $validatedData['role'] ?? Role::USER,
        ]);

        // Optionally, log the user in immediately and return a token
        // $token = $user->createToken('auth_token')->plainTextToken;
        // return response()->json([
        //     'message' => 'User registered successfully.',
        //     'access_token' => $token,
        //     'token_type' => 'Bearer',
        //     'user' => new UserResource($user)
        // ], Response::HTTP_CREATED);

        // Or just return a success message
        return response()->json([
            'message' => 'User registered successfully. Please log in.',
            'user' => new UserResource($user) // Optionally return the created user data
        ], 201);
    }

    /**
     * Authenticate the user and generate a token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            // 'device_name' => 'sometimes|string|max:255', // Optional: for naming the token
        ]);

        if (!Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']])) {
            return response()->json([
                'message' => 'Invalid login credentials.'
            ], 401);
        }

        $user = User::where('email', $credentials['email'])->firstOrFail();

        // Revoke all old tokens for this user if you want single-device login, or manage them differently.
        // $user->tokens()->delete(); // Example: Revoke all previous tokens

        $deviceName = $credentials['device_name'] ?? $request->userAgent() ?? 'Unknown Device';
        $token = $user->createToken($deviceName)->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => new UserResource($user),
            'expires_in' => config('sanctum.expiration') ? (config('sanctum.expiration') * 60) : null // in seconds
        ]);
    }

    /**
     * Get the authenticated User's profile.
     *
     * This route must be protected by 'auth:sanctum' middleware.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function me(Request $request)
    {
        return response()->json(new UserResource($request->user()));
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * This route must be protected by 'auth:sanctum' middleware.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Successfully logged out.'
        ]);
    }

    /**
     * (Optional) Refresh the user's access token.
     * This is a more advanced feature and requires careful implementation
     * to ensure security (e.g., using refresh tokens if Sanctum supported them directly,
     * or by re-authenticating briefly). Sanctum primarily focuses on simple API tokens.
     * For basic Sanctum, re-login is often the simpler approach for a new token.
     *
     * If you need long-lived sessions with refresh capabilities, Laravel Passport (OAuth2)
     * is a more suitable choice.
     *
     * A simple "refresh" by issuing a new token if the current one is valid:
     */
    // public function refresh(Request $request)
    // {
    //     $user = $request->user();
    //     $user->tokens()->where('id', $user->currentAccessToken()->id)->delete(); // Delete current token
    //
    //     $deviceName = $request->userAgent() ?? 'Unknown Device'; // Or get from request
    //     $newToken = $user->createToken($deviceName)->plainTextToken;
    //
    //     return response()->json([
    //         'access_token' => $newToken,
    //         'token_type' => 'Bearer',
    //         'user' => new UserResource($user),
    //         'expires_in' => config('sanctum.expiration') ? (config('sanctum.expiration') * 60) : null
    //     ]);
    // }
}
