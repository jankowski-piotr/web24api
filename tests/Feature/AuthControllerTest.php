<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    private const API_ENDPOINT = '/api/v1/auth/login';
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);
    }
    public function test_user_can_login_with_correct_credentials(): void
    {
        $payload = [
            'email' => 'test@example.com',
            'password' => 'password',
        ];

        $response = $this->postJson(self::API_ENDPOINT, $payload);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                'access_token',
                'token_type',
            ],
        ]);

        $response->assertJsonFragment(['token_type' => 'Bearer']);
    }
    public function test_user_cannot_login_with_invalid_credentials(): void
    {
        $payload = [
            'email' => 'test@example.com',
            'password' => 'wrong-password',
        ];

        $response = $this->postJson(self::API_ENDPOINT, $payload);

        $response->assertStatus(401);

        $response->assertJson([
            'message' => 'Invalid credentials.',
        ]);
    }

    public function test_login_request_requires_email_and_password(): void
    {
        $response = $this->postJson(self::API_ENDPOINT, []);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email', 'password']);
    }
}
