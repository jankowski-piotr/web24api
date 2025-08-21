<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'EmployeeResource',
    properties: [
        new OA\Property(property: 'id', type: 'integer', readOnly: true),
        new OA\Property(property: 'name', type: 'string'),
        new OA\Property(property: 'last_name', type: 'string'),
        new OA\Property(property: 'email', type: 'string', format: 'email'),
        new OA\Property(property: 'phone_number', type: 'string'),
        new OA\Property(property: 'address', ref: '#/components/schemas/AddressResource'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
        new OA\Property(
            property: 'companies',
            type: 'array',
            items: new OA\Items(ref: '#/components/schemas/CompanyResource')
        ),
    ],
    type: 'object'
)]
class EmployeeResource extends JsonResource
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
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'address' => new AddressResource($this->whenLoaded('address')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'companies' => CompanyResource::collection($this->whenLoaded('companies')),
        ];
    }
}
