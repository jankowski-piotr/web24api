<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Resources\EmployeeResource;
use App\Repositories\Interfaces\EmployeeRepositoryInterface;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class EmployeeController extends Controller
{
    public function __construct(private EmployeeRepositoryInterface $employeeRepository) {}

    public function index(): AnonymousResourceCollection
    {
        return EmployeeResource::collection($this->employeeRepository->all());
    }

    public function store(StoreEmployeeRequest $request): EmployeeResource
    {
        $employee = $this->employeeRepository->create($request->validated());
        return new EmployeeResource($employee);
    }
}
