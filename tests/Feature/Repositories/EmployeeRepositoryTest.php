<?php

namespace Tests\Feature\Repositories;

use App\Models\Company;
use App\Models\Employee;
use App\Repositories\EmployeeRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class EmployeeRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private EmployeeRepository $employeeRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->employeeRepository = new EmployeeRepository(new Employee());
    }

    public function test_it_can_retrieve_all_employees(): void
    {
        Employee::factory()->count(3)->create();
        $employees = $this->employeeRepository->all();
        $this->assertCount(3, $employees);
    }

    public function test_it_can_retrieve_all_employees_paginated(): void
    {
        Employee::factory()->count(15)->create();
        $employees = $this->employeeRepository->allPaginated(10);

        $this->assertCount(10, $employees->items());
        $this->assertEquals(2, $employees->lastPage());
    }

    public function test_it_can_find_an_employee_by_id(): void
    {
        $employee = Employee::factory()->create();
        $foundEmployee = $this->employeeRepository->find($employee->id);

        $this->assertNotNull($foundEmployee);
        $this->assertEquals($employee->id, $foundEmployee->id);
    }

    public function test_it_throws_exception_when_employee_is_not_found(): void
    {
        $this->expectException(ModelNotFoundException::class);
        $this->employeeRepository->find(999);
    }

    public function test_it_can_create_an_employee_with_address_and_companies(): void
    {
        $companyA = Company::factory()->create();
        $companyB = Company::factory()->create();

        $data = [
            'name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'hire_date' => now()->format('Y-m-d'),
            'job_id' => 1,
            'salary' => 50000,
            'address' => [
                'street' => '123 Main St',
                'city' => 'Anytown',
                'country_code' => 'USA',
                'postal_code' => '12345',
            ],
            'company_ids' => [$companyA->id, $companyB->id],
        ];

        $newEmployee = $this->employeeRepository->create($data);

        $this->assertNotNull($newEmployee);
        $this->assertEquals($data['email'], $newEmployee->email);
        $this->assertCount(2, $newEmployee->companies);

        $this->assertDatabaseHas('employees', ['email' => $data['email']]);
        $this->assertDatabaseHas('addresses', ['street' => $data['address']['street']]);
        $this->assertDatabaseHas('company_employee', ['employee_id' => $newEmployee->id, 'company_id' => $companyA->id]);
        $this->assertDatabaseHas('company_employee', ['employee_id' => $newEmployee->id, 'company_id' => $companyB->id]);
    }

    public function test_it_can_update_an_employee_address_and_companies(): void
    {
        $employee = Employee::factory()->create();
        $companyA = Company::factory()->create();
        $companyB = Company::factory()->create();

        $employee->companies()->sync([$companyA->id]);

        $updatedData = [
            'name' => 'Jane',
            'last_name' => 'Smith',
            'email' => 'jane.smith@example.com',
            'address' => [
                'street' => '456 New St',
                'city' => 'Newtown',
                'country_code' => 'CAN',
                'postal_code' => '67890',
            ],
            'company_ids' => [$companyB->id],
        ];

        $updatedEmployee = $this->employeeRepository->update($employee->id, $updatedData);

        $this->assertEquals($updatedData['email'], $updatedEmployee->email);
        $this->assertEquals($updatedData['address']['street'], $updatedEmployee->address->street);
        $this->assertCount(1, $updatedEmployee->companies);
        $this->assertEquals($companyB->id, $updatedEmployee->companies->first()->id);

        $this->assertDatabaseHas('employees', ['email' => $updatedData['email']]);
        $this->assertDatabaseHas('addresses', ['street' => $updatedData['address']['street']]);
        $this->assertDatabaseHas('company_employee', ['employee_id' => $employee->id, 'company_id' => $companyB->id]);
        $this->assertDatabaseMissing('company_employee', ['employee_id' => $employee->id, 'company_id' => $companyA->id]);
    }

    public function test_it_can_delete_an_employee(): void
    {
        $employee = Employee::factory()->create();

        $isDeleted = $this->employeeRepository->delete($employee->id);

        $this->assertTrue($isDeleted);
        $this->assertDatabaseMissing('employees', ['id' => $employee->id]);
    }

    public function test_it_deletes_an_employee_but_not_the_address_or_companies(): void
    {
        $employee = Employee::factory()->has(Company::factory()->count(2))->create();

        $addressId = $employee->address->id;
        $companyIds = $employee->companies->pluck('id')->toArray();

        DB::beginTransaction();
        $this->employeeRepository->delete($employee->id);
        DB::commit();

        $this->assertDatabaseMissing('employees', ['id' => $employee->id]);
        $this->assertDatabaseHas('addresses', ['id' => $addressId]);
        $this->assertDatabaseHas('companies', ['id' => $companyIds[0]]);
        $this->assertDatabaseHas('companies', ['id' => $companyIds[1]]);
    }
}
