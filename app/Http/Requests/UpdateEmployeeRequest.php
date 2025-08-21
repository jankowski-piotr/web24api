<?php

namespace App\Http\Requests;

use App\Models\Employee;
use Illuminate\Validation\Rule;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "UpdateEmployeeRequest",
    title: "Update Employee Request",
    required: ["name", "last_name", "email", "address"],
    properties: [
        new OA\Property(property: "name", type: "string", maxLength: 100),
        new OA\Property(property: "last_name", type: "string", maxLength: 100),
        new OA\Property(property: "email", type: "string", format: "email", maxLength: 255),
        new OA\Property(property: "phone_number", type: "string", maxLength: 20),
        new OA\Property(property: "address", type: "object", description: "A valid address object",),
    ]
)]
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
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(Employee::class, 'email'),
            ],
        ];
        return array_merge(parent::rules(), $rules);
    }
}
