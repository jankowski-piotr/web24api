<?php

namespace Tests\Feature\Repositories;

use App\Models\Company;
use App\Models\Employee;
use App\Repositories\CompanyRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CompanyRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private CompanyRepository $companyRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->companyRepository = new CompanyRepository(new Company());
    }

    public function test_it_can_retrieve_all_companies(): void
    {
        Company::factory()->count(3)->create();
        $companies = $this->companyRepository->all();
        $this->assertCount(3, $companies);
    }

    public function test_it_can_retrieve_all_companies_paginated(): void
    {
        Company::factory()->count(15)->create();
        $companies = $this->companyRepository->allPaginated(10);
        $this->assertCount(10, $companies->items());
        $this->assertEquals(2, $companies->lastPage());
    }

    public function test_it_can_find_a_company_by_id(): void
    {
        $company = Company::factory()->create();
        $foundCompany = $this->companyRepository->find($company->id);

        $this->assertNotNull($foundCompany);
        $this->assertEquals($company->id, $foundCompany->id);
    }

    public function test_it_throws_exception_when_company_is_not_found(): void
    {
        $this->expectException(ModelNotFoundException::class);
        $this->companyRepository->find(999);
    }

    public function test_it_can_create_a_company_with_a_new_address(): void
    {
        $data = [
            'name' => 'New Company',
            'tax_number' => '1234567890',
            'address' => [
                'street' => '123 Test Street',
                'city' => 'Test City',
                'country_code' => 'USA',
                'postal_code' => '12345',
            ],
        ];

        $newCompany = $this->companyRepository->create($data);

        $this->assertNotNull($newCompany);
        $this->assertEquals($data['name'], $newCompany->name);
        $this->assertDatabaseHas('companies', ['name' => $data['name']]);
        $this->assertDatabaseHas('addresses', $data['address']);
        $this->assertEquals($newCompany->address->street, $data['address']['street']);
    }

    public function test_it_can_update_a_company_and_its_address(): void
    {
        $company = Company::factory()->create();

        $updatedData = [
            'name' => 'Updated Company Name',
            'tax_number' => '0987654321',
            'address' => [
                'street' => 'Updated Street',
                'city' => 'Updated City',
                'country_code' => 'CAN',
                'postal_code' => '54321',
            ],
        ];

        $updatedCompany = $this->companyRepository->update($company->id, $updatedData);

        $this->assertEquals($updatedData['name'], $updatedCompany->name);
        $this->assertEquals($updatedData['address']['street'], $updatedCompany->address->street);

        $this->assertDatabaseHas('companies', [
            'id' => $company->id,
            'name' => $updatedData['name'],
        ]);
    }

    public function test_it_can_delete_a_company(): void
    {
        $company = Company::factory()->create();

        $isDeleted = $this->companyRepository->delete($company->id);

        $this->assertTrue($isDeleted);
        $this->assertDatabaseMissing('companies', ['id' => $company->id]);
    }

    public function test_it_deletes_a_company_but_not_the_address_or_employees(): void
    {
        $company = Company::factory()->has(Employee::factory()->count(2))->create();

        $addressId = $company->address->id;
        $employeeIds = $company->employees->pluck('id')->toArray();

        DB::beginTransaction();
        $this->companyRepository->delete($company->id);
        DB::commit();

        $this->assertDatabaseMissing('companies', ['id' => $company->id]);
        $this->assertDatabaseHas('addresses', ['id' => $addressId]);
        $this->assertDatabaseHas('employees', ['id' => $employeeIds[0]]);
        $this->assertDatabaseHas('employees', ['id' => $employeeIds[1]]);
    }
}
