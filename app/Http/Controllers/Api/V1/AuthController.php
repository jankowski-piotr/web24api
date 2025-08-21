<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Http\Resources\AuthResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: "Auth",
    description: "API Endpoints login and authentication"
)]

class AuthController extends Controller
{
    
    #[OA\Get(
        path: "/api/v1/login",
        summary: "Login and get authentication token",
        tags: ["Auth"],
        security: [["Token-Based Authentication" => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: "OK",
                content: new OA\JsonContent(ref: AuthResource::class)
            ),
            new OA\Response(
                response: 401,
                description: "Unauthenticated"
            )
        ]
    )]
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
