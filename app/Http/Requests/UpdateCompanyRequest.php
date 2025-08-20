<?php

namespace App\Http\Requests;

use App\Models\Company;
use Illuminate\Validation\Rule;

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
