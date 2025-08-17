<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use App\Models\Company;

interface CompanyRepositoryInterface
{
    public function all(): Collection;

    public function find(int $id): ?Company;

    public function create(array $data): Company;

    public function update(int $id, array $data): ?Company;

    public function delete(int $id): bool;
}
