<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\EmployeeResource;
use App\Repositories\Interfaces\EmployeeRepositoryInterface;
use Illuminate\Http\JsonResponse;

class EmployeeController extends Controller
{
    public function __construct(private EmployeeRepositoryInterface $employeeRepository) {}

    // GET /api/v1/employees
    public function index(): BaseCollection
    {
        $paginator = $this->employeeRepository->allWithAddressesPaginated();
        return new BaseCollection($paginator);
    }
    // POST /api/v1/employees
    public function store(StoreEmployeeRequest $request): EmployeeResource
    {
        $employee = $this->employeeRepository->create($request->validated());
        return new EmployeeResource($employee);
    }

    // GET /api/v1/employees/{id}
    public function show(int $id): EmployeeResource
    {
        $employee = $this->employeeRepository->findWithAddress($id);
        return new EmployeeResource($employee);
    }

    // PUT / PATCH /api/v1/employees/{id}
    public function update(UpdateEmployeeRequest $request, int $id): EmployeeResource
    {
        $employee = $this->employeeRepository->update($id, $request->validated());
        return new EmployeeResource($employee);
    }

    // DELETE /api/v1/employees/{id}
    public function destroy(int $id): JsonResponse
    {
        $this->employeeRepository->delete($id);
        return response()->json(null, 204);
    }
}
