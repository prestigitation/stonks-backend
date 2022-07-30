<?php
namespace App\Interfaces;

use App\Http\Requests\RegisterUserRequest;
use Illuminate\Http\JsonResponse;

interface AuthServiceInterface {
    public function register(array $credentials): JsonResponse;
}
