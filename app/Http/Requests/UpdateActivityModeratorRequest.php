<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateActivityModeratorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'activity_material_id' => 'sometimes|required|exists:activity_materials,id',
            'user_id' => 'sometimes|required|exists:users,id',
        ];
    }
}
