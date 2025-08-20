<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Company;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    public function run(): void
    {
        $addresses = Address::factory()
            ->count(100)
            ->create();

        foreach ($addresses as $address) {
            Company::create([
                'name' => fake()->company,
                'tax_number' => fake()->unique()->numerify('###########'),
                'address_id' => $address->id,
            ]);
        }
    }
}
