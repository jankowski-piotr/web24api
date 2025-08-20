<?php

namespace App\Http\Requests;

use App\Models\Company;
use App\Rules\AddressValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CompanyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100', Rule::unique(Company::class, 'name'),],
            'tax_number' => ['required', 'string', 'digits_between:1,13', Rule::unique(Company::class, 'tax_number')],
            'address' => ['required', 'array', new AddressValidationRule()],
        ];
    }
}
