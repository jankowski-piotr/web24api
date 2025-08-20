<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Http\Resources\AuthResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(AuthRequest $request): AuthResource|JsonResponse
    {
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => __('Invalid credentials.')
            ], 401);
        }

        $user = $request->user();
        $token = $user->createToken(
            'auth_token',
            ['*'],
            Carbon::now()->addMinutes(60)
        )->plainTextToken;

        return new AuthResource([
            'user'  => $user,
            'token' => $token,
        ]);
    }
}
