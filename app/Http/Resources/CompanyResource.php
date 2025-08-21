<?php

namespace App\Http\Resources;

use App\Http\Resources\EmployeeResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'CompanyResource',
    properties: [
        new OA\Property(property: 'id', type: 'integer', readOnly: true),
        new OA\Property(property: 'name', type: 'string'),
        new OA\Property(property: 'tax_number', type: 'string'),
        new OA\Property(property: 'address', ref: '#/components/schemas/AddressResource'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
        new OA\Property(
            property: 'employees',
            type: 'array',
            items: new OA\Items(ref: '#/components/schemas/EmployeeResource')
        ),
    ],
    type: 'object'
)]
class CompanyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'tax_number' => $this->tax_number,
            'address' => new AddressResource($this->whenLoaded('address')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'employees' => EmployeeResource::collection($this->whenLoaded('employees')),
        ];
    }
}
