<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Company;
use App\Models\Employee;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $addresses = Address::factory()
            ->count(5)
            ->create();

        foreach ($addresses as $address) {
            Employee::create([
                'name'         => fake()->firstName,
                'last_name'    => fake()->lastName,
                'email'        => fake()->unique()->safeEmail,
                'phone_number' => fake()->optional(0.8)->phoneNumber,
                'address_id'   => $address->id,
            ]);
        }
    }
}
