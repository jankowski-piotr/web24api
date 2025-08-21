<?php

namespace App\Repositories;

use App\Models\Address;
use App\Models\Employee;
use App\Repositories\Interfaces\EmployeeRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EmployeeRepository implements EmployeeRepositoryInterface
{
    public function __construct(protected Employee $model) {}

    public function all(): Collection
    {
        return $this->model->all();
    }
    public function allPaginated(int $perPage = 10): LengthAwarePaginator
    {
        return $this->model->with('address', 'companies.address')->paginate($perPage);
    }

    public function find($id): ?Employee
    {
        return $this->model->with('address', 'companies.address')->findOrFail($id);
    }

    public function create(array $data): Employee
    {
        try {
            DB::beginTransaction();

            $address = Address::firstOrCreate($data['address']);
            $employee = new Employee(Arr::except($data, ['address']));

            $employee->address()->associate($address);
            $employee->save();
            $employee->companies()->sync($data['company_ids'] ?? []);

            DB::commit();
            return $employee->fresh('companies.address');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Employee creation failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function update($id, array $data): ?Employee
    {
        try {
            DB::beginTransaction();

            $address = Address::firstOrCreate($data['address']);
            $employee = $this->model->findOrFail($id);

            $employee->address()->associate($address);
            $employee->fill(Arr::except($data, ['address']));
            $employee->update();
            $employee->companies()->sync($data['company_ids'] ?? []);

            DB::commit();
            return $employee->fresh('companies.address');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Employee update failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function delete($id): bool
    {
        $employee = $this->model->findOrFail($id);
        return $employee->delete();
    }
}
