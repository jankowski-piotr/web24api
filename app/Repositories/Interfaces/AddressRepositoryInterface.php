<?php

namespace App\Repositories\Interfaces;

use App\Models\Address;

interface AddressRepositoryInterface extends BaseRepositoryInterface
{
    public function find(int $id): ?Address;

    public function create(array $data): Address;

    public function update(int $id, array $data): ?Address;

}
