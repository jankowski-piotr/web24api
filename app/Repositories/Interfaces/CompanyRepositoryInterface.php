<?php

namespace App\Repositories\Interfaces;

use App\Models\Company;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface CompanyRepositoryInterface extends BaseRepositoryInterface
{
    public function allWithAddressesPaginated(int $perPage = 10): LengthAwarePaginator;

    public function find(int $id): ?Company;

    public function findWithAddress(int $id): ?Company;

    public function create(array $data): Company;

    public function update(int $id, array $data): ?Company;
}
