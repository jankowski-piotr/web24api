<?php

namespace App\Repositories;

use App\Models\Address;
use App\Models\Company;
use App\Repositories\Interfaces\CompanyRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CompanyRepository implements CompanyRepositoryInterface
{
    public function __construct(protected Company $model) {}


    public function all(): Collection
    {
        return $this->model->all();
    }

    public function allWithAddressesPaginated(int $perPage = 10): LengthAwarePaginator
    {
        return $this->model->with('address')->paginate($perPage);
    }

    public function find($id): ?Company
    {
        return $this->model->findOrFail($id);
    }

    public function findWithAddress(int $id): ?Company
    {
        return $this->model->with('address')->findOrFail($id);
    }

    public function create(array $data): Company
    {
        try {
            DB::beginTransaction();

            $address = Address::firstOrCreate($data['address']);
            $company = new Company(Arr::except($data, ['address']));

            $company->address()->associate($address);
            $company->save();

            DB::commit();
            return $company;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Company creation failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function update($id, array $data): ?Company
    {
        try {
            DB::beginTransaction();

            $address = Address::firstOrCreate($data['address']);
            $company = $this->model->findOrFail($id);

            $company->address()->associate($address);
            $company->fill(Arr::except($data, ['address']));
            $company->update();

            DB::commit();
            return $company;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Employee update failed: ' . $e->getMessage());
            throw $e;
        }
    }


    public function delete($id): bool
    {
        $user = $this->model->findOrFail($id);
        return $user->delete();
    }
}
