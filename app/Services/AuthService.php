<?php
namespace App\Services;

use App\Http\Requests\RegisterUserRequest;
use App\Interfaces\AuthServiceInterface;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class AuthService implements AuthServiceInterface {
    public function login(array $credentials)
    {
        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }
    public function register(array $credentials): JsonResponse
    {
        $loginData = collect($credentials)->only(['email', 'password'])->all();
        $credentials['password'] = Hash::make($credentials['password']);
        User::create($credentials);
        return response()->json($this->login($loginData));
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }
}
