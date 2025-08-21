<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\CompanyResource;
use App\Repositories\Interfaces\CompanyRepositoryInterface;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: "Companies",
    description: "API Endpoints for Companies"
)]
class CompanyController extends Controller
{
    public function __construct(private CompanyRepositoryInterface $companyRepository) {}

    #[OA\Get(
        path: "/api/v1/companies",
        summary: "Get a list of companies",
        tags: ["Companies"],
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
        $paginator = $this->companyRepository->allWithAddressesPaginated();
        return new BaseCollection($paginator);
    }

    #[OA\Post(
        path: "/api/v1/companies",
        summary: "Create a new company",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: "#/components/schemas/StoreCompanyRequest"
            )
        ),
        tags: ["Companies"],
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
    public function store(StoreCompanyRequest $request): CompanyResource
    {
        $company = $this->companyRepository->create($request->validated());
        return new CompanyResource($company);
    }

    #[OA\Get(
        path: "/api/v1/companies/{id}",
        summary: "Get a single company by ID",
        tags: ["Companies"],
        parameters: [
            new OA\Parameter(
                name: "id",
                description: "Company ID",
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
    public function show(int $id): CompanyResource
    {
        $company = $this->companyRepository->findWithAddress($id);
        return new CompanyResource($company);
    }

    #[OA\Put(
        path: "/api/v1/companies/{id}",
        summary: "Update an existing company",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: "#/components/schemas/UpdateCompanyRequest")
        ),
        tags: ["Companies"],
        parameters: [
            new OA\Parameter(
                name: "id",
                description: "Company ID",
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
    public function update(UpdateCompanyRequest $request, int $id): CompanyResource
    {
        $company = $this->companyRepository->update($id, $request->validated());
        return new CompanyResource($company);
    }

    #[OA\Delete(
        path: "/api/v1/companies/{id}",
        summary: "Delete a company",
        tags: ["Companies"],
        parameters: [
            new OA\Parameter(
                name: "id",
                description: "Company ID",
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
        $this->companyRepository->delete($id);
        return response()->json(null, 204);
    }
}