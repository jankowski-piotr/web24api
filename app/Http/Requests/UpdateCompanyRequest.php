<?php

namespace App\Http\Requests;

use App\Models\Company;
use Illuminate\Validation\Rule;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "UpdateCompanyRequest",
    title: "Update Company Request",
    required: ["name", "tax_number", "address"],
    properties: [
        new OA\Property(property: "name", type: "string", maxLength: 100),
        new OA\Property(property: "tax_number", type: "string", description: "1 to 13 digits", minLength: 1, maxLength: 13),
        new OA\Property(property: "address", type: "object", description: "A valid address object"),
    ]
)]
class UpdateCompanyRequest extends CompanyRequest
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
        $company = $this->route('company');

        $rules = [
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique(Company::class, 'name')->ignore($company),
            ],
            'tax_number' => [
                'required',
                'string',
                'digits_between:1,13',
                Rule::unique(Company::class, 'tax_number')->ignore($company),
            ],
        ];
        return array_merge(parent::rules(), $rules);
    }
}
