<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreActivitySpeakerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'activity_material_id' => 'required|exists:activity_materials,id',
            'user_id' => 'required|exists:users,id',
        ];
    }
}
