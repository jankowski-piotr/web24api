<?php

namespace App\Repositories\Interfaces;

use App\Models\Employee;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface EmployeeRepositoryInterface
{
    public function allWithAddressesPaginated(int $perPage = 15): LengthAwarePaginator;

    public function all(): Collection;

    public function find(int $id): ?Employee;

    public function create(array $data): Employee;

    public function update(int $id, array $data): ?Employee;

    public function delete(int $id): bool;
}
