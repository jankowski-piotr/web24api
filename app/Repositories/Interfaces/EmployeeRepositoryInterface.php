<?php

namespace App\Repositories\Interfaces;

use App\Models\Employee;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface EmployeeRepositoryInterface extends BaseRepositoryInterface
{
    public function allWithAddressesPaginated(int $perPage = 10): LengthAwarePaginator;

    public function find(int $id): ?Employee;

    public function findWithAddress(int $id): ?Employee;

    public function create(array $data): Employee;

    public function update(int $id, array $data): ?Employee;
}
