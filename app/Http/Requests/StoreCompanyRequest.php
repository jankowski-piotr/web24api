<?php

namespace App\Http\Requests;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "StoreCompanyRequest",
    title: "Store Company Request",
    required: ["name", "tax_number", "address"],
    properties: [
        new OA\Property(property: "name", type: "string", maxLength: 100),
        new OA\Property(property: "tax_number", type: "string", description: "1 to 13 digits", minLength: 1, maxLength: 13),
        new OA\Property(property: "address", type: "object", description: "A valid address object"),
    ]
)]
class StoreCompanyRequest extends CompanyRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return parent::rules();
    }
}
