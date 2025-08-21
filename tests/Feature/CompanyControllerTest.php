<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CompanyControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private const API_ENDPOINT = '/api/v1/companies';

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_guest_cannot_retrieve_companies(): void
    {
        $this->getJson(self::API_ENDPOINT)->assertStatus(401);
    }

    public function test_authenticated_user_can_retrieve_companies(): void
    {
        Company::factory()->count(5)->create();
        $response = $this->actingAs($this->user)->getJson(self::API_ENDPOINT);
        $response->assertStatus(200);
        $response->assertJsonCount(5, 'data');

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'tax_number',
                    'address' => [
                        'street',
                        'city',
                        'country_code',
                        'postal_code',
                        'created_at',
                        'updated_at',
                    ],
                    'created_at',
                    'updated_at',
                ],
            ],
            'meta' => [
                'current_page',
                'last_page',
                'from',
                'to',
                'total',
            ],
        ]);
    }

    public function test_authenticated_user_can_create_a_company(): void
    {
        $payload = [
            'name' => $this->faker->company,
            'tax_number' => (string)$this->faker->unique()->randomNumber(9, true),
            'address' => [
                'street' => $this->faker->streetAddress,
                'city' => $this->faker->city,
                'country_code' => $this->faker->countryISOAlpha3(),
                'postal_code' => $this->faker->postcode,
            ],
        ];

        $response = $this->actingAs($this->user)->postJson(self::API_ENDPOINT, $payload);
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'tax_number',
                'address' => [
                    'street',
                    'city',
                    'country_code',
                    'postal_code',
                    'created_at',
                    'updated_at',
                ],
                'created_at',
                'updated_at',
            ],
        ])->assertJsonFragment([
            'name' => $payload['name'],
            'tax_number' => $payload['tax_number'],
            'street' => $payload['address']['street'],
        ]);

        $this->assertDatabaseHas('companies', [
            'name' => $payload['name'],
            'tax_number' => $payload['tax_number'],
        ]);
    }
    public function test_create_company_request_requires_valid_data(): void
    {
        $payload = [];
        $response = $this->actingAs($this->user)->postJson(self::API_ENDPOINT, $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name', 'tax_number', 'address']);
    }
    public function test_authenticated_user_can_retrieve_a_company(): void
    {
        $company = Company::factory()->create();
        $response = $this->actingAs($this->user)->getJson(self::API_ENDPOINT . '/' . $company->id);
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id' => $company->id,
            'name' => $company->name,
            'tax_number' => $company->tax_number,
        ]);
    }

    public function test_authenticated_user_can_update_a_company(): void
    {
        $company = Company::factory()->create();
        $payload = [
            'name' => 'Updated Company Name',
            'tax_number' => '1234567890',
            'address' => [
                'street' => 'New Street Name',
                'city' => 'New City',
                'country_code' => 'USA',
                'postal_code' => '12345',
            ],
        ];

        $response = $this->actingAs($this->user)->putJson(self::API_ENDPOINT . '/' . $company->id, $payload);
        $response->assertStatus(200);

        $this->assertDatabaseHas('companies', [
            'id' => $company->id,
            'name' => $payload['name'],
            'tax_number' => $payload['tax_number'],
        ]);

        $this->assertDatabaseHas('addresses', [
            'street' => $payload['address']['street'],
            'city' => $payload['address']['city'],
        ]);
    }

    public function test_authenticated_user_can_delete_a_company(): void
    {
        $company = Company::factory()->create();
        $response = $this->actingAs($this->user)->deleteJson(self::API_ENDPOINT . '/' . $company->id);
        $response->assertStatus(204);

        $this->assertDatabaseMissing('companies', [
            'id' => $company->id,
        ]);
    }
}
