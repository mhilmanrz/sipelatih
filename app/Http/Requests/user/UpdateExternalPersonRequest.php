<?php

namespace App\Http\Requests\user;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateExternalPersonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'nik' => 'nullable|string|max:255',
            'employee_id' => 'nullable|string|max:255',
            'npwp' => 'nullable|string|max:255',
            'institution' => 'nullable|string|max:255',
            'external_position' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255|unique:users,email,'.$this->route('id'),
            'is_narasumber' => 'sometimes|boolean',
            'is_moderator' => 'sometimes|boolean',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if (! $this->boolean('is_narasumber') && ! $this->boolean('is_moderator')) {
                $validator->errors()->add('is_narasumber', 'Pilih minimal satu kapasitas: Narasumber atau Moderator.');
            }
        });
    }
}
