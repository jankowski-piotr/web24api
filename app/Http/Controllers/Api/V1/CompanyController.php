<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\CompanyResource;
use App\Repositories\Interfaces\CompanyRepositoryInterface;
use Illuminate\Http\JsonResponse;

class CompanyController extends Controller
{
    public function __construct(private CompanyRepositoryInterface $companyRepository) {}

    // GET /api/v1/companies
    public function index(): BaseCollection
    {
        $paginator = $this->companyRepository->allWithAddressesPaginated();
        return new BaseCollection($paginator);
    }
    // POST /api/v1/companies
    public function store(StoreCompanyRequest $request): CompanyResource
    {
        $company = $this->companyRepository->create($request->validated());
        return new CompanyResource($company);
    }

    // GET /api/v1/companies/{id}
    public function show(int $id): CompanyResource
    {
        $company = $this->companyRepository->findWithAddress($id);
        return new CompanyResource($company);
    }

    // PUT/PATCH /api/v1/companies/{id}
    public function update(UpdateCompanyRequest $request, int $id): CompanyResource
    {
        $company = $this->companyRepository->update($id, $request->validated());
        return new CompanyResource($company);
    }

    // DELETE /api/v1/companies/{id}
    public function destroy(int $id): JsonResponse
    {
        $this->companyRepository->delete($id);
        return response()->json(null, 204);
    }
}