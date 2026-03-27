<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
        return [
            'name'               => 'required|string|max:255',
            'email'              => 'required|string|email|max:255|unique:users',
            'password'           => 'required|string|min:8',
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
