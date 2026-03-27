<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
     */
    public function rules(): array
    {
        // Support both API routes using parameter 'id' and Web routes using parameter 'user'
        $userId = $this->route('user') ?? $this->route('id');

        return [
            'name'               => 'sometimes|required|string|max:255',
            'email'              => 'sometimes|required|string|email|max:255|unique:users,email,' . $userId,
            'password'           => 'nullable|string|min:8',
            'employee_id'        => 'nullable|string',
            'phone_number'       => 'nullable|string',
            'work_unit_id'       => 'nullable|exists:work_units,id',
            'profession_id'      => 'nullable|exists:professions,id',
            'position_id'        => 'nullable|exists:positions,id',
            'employment_type_id' => 'nullable|exists:employment_types,id',
            'jpl_target'         => 'nullable|integer',
        ];
    }
}
