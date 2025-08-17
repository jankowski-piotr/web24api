<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use App\Models\Address;

interface AddressRepositoryInterface
{
    public function all(): Collection;

    public function find(int $id): ?Address;

    public function create(array $data): Address;

    public function update(int $id, array $data): ?Address;

    public function delete(int $id): bool;
}
