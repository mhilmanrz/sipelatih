<?php

namespace App\Http\Requests\user;

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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'work_unit_id' => 'nullable|integer|exists:work_units,id',
            'position_id' => 'nullable|integer|exists:positions,id',
            'employment_type_id' => 'nullable|integer|exists:employment_types,id',
            'profession_id' => 'nullable|integer|exists:professions,id',
            'employee_id' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:255',
        ];
    }
}
