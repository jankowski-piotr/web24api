<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Employee;
use Illuminate\Database\Seeder;

class EmployeeCompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companies = Company::all();
        $employees = Employee::all();

        foreach ($companies as $company) {
            $randomEmployees = $employees->random(rand(1, 5));
            $company->employees()->attach($randomEmployees);
        }
    }
}