<?php

namespace App\Repositories;

use App\Models\Employee;
use App\Repositories\Interfaces\EmployeeRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class EmployeeRepository implements EmployeeRepositoryInterface
{
    public function __construct(protected Employee $model) {}

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function find($id): ?Employee
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data): Employee
    {
        return $this->model->create($data);
    }

    public function update($id, array $data): ?Employee
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
