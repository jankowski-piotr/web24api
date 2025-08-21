<?php

namespace Tests\Feature\Repositories;

use App\Models\Address;
use App\Repositories\AddressRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AddressRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private AddressRepository $addressRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->addressRepository = new AddressRepository(new Address());
    }

    public function test_it_can_retrieve_all_addresses(): void
    {
        Address::factory()->count(3)->create();
        $addresses = $this->addressRepository->all();
        $this->assertCount(3, $addresses);
    }
    public function test_it_can_find_an_address_by_id(): void
    {
        $address = Address::factory()->create();
        $foundAddress = $this->addressRepository->find($address->id);
        $this->assertNotNull($foundAddress);
        $this->assertEquals($address->id, $foundAddress->id);
    }

    public function test_it_throws_exception_when_address_is_not_found(): void
    {
        $this->expectException(ModelNotFoundException::class);
        $this->addressRepository->find(999);
    }

    public function test_it_can_create_an_address(): void
    {
        $data = [
            'street' => '123 Test Street',
            'city' => 'Test City',
            'country_code' => 'USA',
            'postal_code' => '12345',
        ];

        $newAddress = $this->addressRepository->create($data);

        $this->assertNotNull($newAddress);
        $this->assertEquals($data['street'], $newAddress->street);
        $this->assertDatabaseHas('addresses', $data);
    }

    public function test_it_can_update_an_address(): void
    {
        $address = Address::factory()->create();
        $updatedData = [
            'street' => 'Updated Test Street',
            'city' => 'Updated City',
        ];

        $updatedAddress = $this->addressRepository->update($address->id, $updatedData);
        $this->assertEquals($updatedData['street'], $updatedAddress->street);
        $this->assertDatabaseHas('addresses', [
            'id' => $address->id,
            'street' => $updatedData['street'],
            'city' => $updatedData['city'],
        ]);
    }

    public function test_it_can_delete_an_address(): void
    {
        $address = Address::factory()->create();
        $isDeleted = $this->addressRepository->delete($address->id);
        $this->assertTrue($isDeleted);

        $this->assertDatabaseMissing('addresses', ['id' => $address->id]);
    }
}
