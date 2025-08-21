<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\EmployeeResource;
use App\Repositories\Interfaces\EmployeeRepositoryInterface;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: "Employees",
    description: "API Endpoints for Employees"
)]
class EmployeeController extends Controller
{
    public function __construct(private EmployeeRepositoryInterface $employeeRepository) {}

    #[OA\Get(
        path: "/api/v1/employees",
        summary: "Get a list of employees",
        tags: ["Employees"],
        security: [["sanctum" => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: "OK",
                content: new OA\JsonContent()
            ),
            new OA\Response(
                response: 401,
                description: "Unauthenticated"
            )
        ]
    )]
    public function index(): BaseCollection
    {
        $paginator = $this->employeeRepository->allPaginated();
        return new BaseCollection($paginator);
    }

    #[OA\Post(
        path: "/api/v1/employees",
        summary: "Create a new employee",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: "#/components/schemas/StoreEmployeeRequest"
            )
        ),
        tags: ["Employees"],
        security: [["sanctum" => []]],
        responses: [
            new OA\Response(
                response: 201,
                description: "Created",
                content: new OA\JsonContent()
            ),
            new OA\Response(
                response: 401,
                description: "Unauthenticated"
            ),
            new OA\Response(
                response: 422,
                description: "Validation failed"
            )
        ]
    )]
    public function store(StoreEmployeeRequest $request): EmployeeResource
    {
        $employee = $this->employeeRepository->create($request->validated());
        return new EmployeeResource($employee);
    }

    #[OA\Get(
        path: "/api/v1/employees/{id}",
        summary: "Get a single employee by ID",
        tags: ["Employees"],
        parameters: [
            new OA\Parameter(
                name: "id",
                description: "Employee ID",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        security: [["sanctum" => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: "OK",
                content: new OA\JsonContent()
            ),
            new OA\Response(
                response: 401,
                description: "Unauthenticated"
            ),
            new OA\Response(
                response: 404,
                description: "Not Found"
            )
        ]
    )]
    public function show(int $id): EmployeeResource
    {
        $employee = $this->employeeRepository->find($id);
        return new EmployeeResource($employee);
    }

    #[OA\Put(
        path: "/api/v1/employees/{id}",
        summary: "Update an existing employee",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: "#/components/schemas/UpdateEmployeeRequest")
        ),
        tags: ["Employees"],
        parameters: [
            new OA\Parameter(
                name: "id",
                description: "Employee ID",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        security: [["sanctum" => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: "OK",
                content: new OA\JsonContent()
            ),
            new OA\Response(
                response: 401,
                description: "Unauthenticated"
            ),
            new OA\Response(
                response: 404,
                description: "Not Found"
            ),
            new OA\Response(
                response: 422,
                description: "Validation failed"
            )
        ]
    )]
    public function update(UpdateEmployeeRequest $request, int $id): EmployeeResource
    {
        $employee = $this->employeeRepository->update($id, $request->validated());
        return new EmployeeResource($employee);
    }

    #[OA\Delete(
        path: "/api/v1/employees/{id}",
        summary: "Delete an employee",
        tags: ["Employees"],
        parameters: [
            new OA\Parameter(
                name: "id",
                description: "Employee ID",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        security: [["sanctum" => []]],
        responses: [
            new OA\Response(
                response: 204,
                description: "No Content"
            ),
            new OA\Response(
                response: 401,
                description: "Unauthenticated"
            ),
            new OA\Response(
                response: 404,
                description: "Not Found"
            )
        ]
    )]
    public function destroy(int $id): JsonResponse
    {
        $this->employeeRepository->delete($id);
        return response()->json(null, 204);
    }
}