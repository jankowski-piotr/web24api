<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'AuthResource',
    properties: [
        new OA\Property(
            property: 'access_token',
            type: 'string',
            description: 'The access token used for authentication.'
        ),
        new OA\Property(
            property: 'token_type',
            type: 'string',
            description: 'The type of token, typically "Bearer".'
        ),
    ],
    type: 'object'
)]
class AuthResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'access_token' => $this->resource['token'],
            'token_type'   => 'Bearer',
        ];
    }
}
