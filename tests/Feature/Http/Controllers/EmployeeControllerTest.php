<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Company;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EmployeeControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private const API_ENDPOINT = '/api/v1/employees';

    private User $user;
    private Company $company;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->company = Company::factory()->create();
    }

    public function test_guest_cannot_retrieve_employees(): void
    {
        $this->getJson(self::API_ENDPOINT)->assertStatus(401);
    }

    public function test_authenticated_user_can_retrieve_employees(): void
    {
        $this->company->employees()->attach(Employee::factory()->count(5)->create());
        $response = $this->actingAs($this->user)->getJson(self::API_ENDPOINT);
        $response->assertStatus(200);
        $response->assertJsonCount(5, 'data');
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'last_name',
                    'email',
                    'phone_number',
                    'address' => [
                        'id',
                        'street',
                        'city',
                        'postal_code',
                    ],
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

    public function test_authenticated_user_can_create_an_employee(): void
    {
        $payload = [
            'name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'phone_number' => null,
            'address' => [
                'street' => $this->faker->streetAddress,
                'city' => $this->faker->city,
                'country_code' => $this->faker->countryISOAlpha3(),
                'postal_code' => $this->faker->postcode,
            ],
            'company_ids' => [$this->company->id],
        ];

        $response = $this->actingAs($this->user)->postJson(self::API_ENDPOINT, $payload);
        $response->assertStatus(201);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'last_name',
                'email',
                'phone_number',
                'address' => [
                    'street',
                    'city',
                    'country_code',
                    'postal_code',
                ],
                'companies' => [
                    '*' => [
                        'id',
                        'name',
                        'tax_number',
                        'address' => [
                            'street',
                            'city',
                            'country_code',
                            'postal_code',
                        ],
                        'created_at',
                        'updated_at',
                    ],
                ],
            ],
        ]);

        $response->assertJsonFragment([
            'email' => $payload['email'],
        ]);

        $this->assertDatabaseHas('company_employee', [
            'employee_id' => $response->json('data.id'),
            'company_id' => $this->company->id,
        ]);

        $this->assertDatabaseHas('employees', [
            'email' => $payload['email'],
        ]);
    }

    public function test_create_employee_request_requires_valid_data(): void
    {
        $payload = [];
        $response = $this->actingAs($this->user)->postJson(self::API_ENDPOINT, $payload);
        $response->assertStatus(422);

        $response->assertJsonValidationErrors([
            'name',
            'last_name',
            'email',
            'address',
        ]);
    }

    public function test_authenticated_user_can_retrieve_an_employee(): void
    {
        $employee = Employee::factory()->create();
        $response = $this->actingAs($this->user)->getJson(self::API_ENDPOINT . '/' . $employee->id);
        $response->assertStatus(200);

        $response->assertJsonFragment([
            'id' => $employee->id,
            'email' => $employee->email,
        ]);
    }

    public function test_authenticated_user_can_update_an_employee(): void
    {
        $employee = Employee::factory()->create();
        $payload = [
            'name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'phone_number' => null,
            'address' => [
                'street' => $this->faker->streetAddress,
                'city' => $this->faker->city,
                'country_code' => $this->faker->countryISOAlpha3(),
                'postal_code' => $this->faker->postcode,
            ],
            'company_ids' => [$this->company->id],
        ];

        $response = $this->actingAs($this->user)->putJson(self::API_ENDPOINT . '/' . $employee->id, $payload);
        $response->assertStatus(200);

        $this->assertDatabaseHas('employees', [
            'id' => $employee->id,
            'email' => $payload['email'],
        ]);
    }
    public function test_authenticated_user_can_delete_an_employee(): void
    {
        $employee = Employee::factory()->create();
        $response = $this->actingAs($this->user)->deleteJson(self::API_ENDPOINT . '/' . $employee->id);
        $response->assertStatus(204);

        $this->assertDatabaseMissing('employees', [
            'id' => $employee->id,
        ]);
    }
}
