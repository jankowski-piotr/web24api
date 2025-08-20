<?php

namespace App\Http\Requests;

use App\Models\Employee;
use Illuminate\Validation\Rule;

class UpdateEmployeeRequest extends EmployeeRequest
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
        $employee = $this->route('employee');

        $rules = [
            'email' => [
                Rule::unique(Employee::class, 'email')->ignore($employee),
            ],
        ];
        return array_merge(parent::rules(), $rules);
    }
}
