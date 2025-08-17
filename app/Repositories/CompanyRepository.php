<?php

namespace App\Repositories;

use App\Models\Company;
use App\Repositories\Interfaces\CompanyRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CompanyRepository implements CompanyRepositoryInterface
{
    public function __construct(protected Company $model) {}

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function find($id): ?Company
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data): Company
    {
        return $this->model->create($data);
    }

    public function update($id, array $data): ?Company
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
