<?php

namespace App\Repositories;

use App\Models\Address;
use App\Repositories\Interfaces\AddressRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class AddressRepository implements AddressRepositoryInterface
{
    public function __construct(protected Address $model) {}

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function find($id): ?Address
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data): Address
    {
        return $this->model->create($data);
    }

    public function update($id, array $data): ?Address
    {
        $user = $this->model->findOrFail($id);
        $user->update($data);
        return $user;
    }

    public function delete($id): bool
    {
        $user = $this->model->findOrFail($id);
        return $user->delete();
    }
}
