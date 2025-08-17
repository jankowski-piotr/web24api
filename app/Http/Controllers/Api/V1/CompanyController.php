<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Resources\CompanyResource;
use App\Repositories\Interfaces\CompanyRepositoryInterface;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CompanyController extends Controller
{
    public function __construct(private CompanyRepositoryInterface $companyRepository) {}

    public function index(): AnonymousResourceCollection
    {
        return CompanyResource::collection($this->companyRepository->all());
    }

    public function store(StoreCompanyRequest $request): CompanyResource
    {
        $company = $this->companyRepository->create($request->validated());
        return new CompanyResource($company);
    }
}
