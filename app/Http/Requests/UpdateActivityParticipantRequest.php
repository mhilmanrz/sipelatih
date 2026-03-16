<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateActivityParticipantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'certificate_number' => 'sometimes|nullable|string|max:255',
            'is_passed' => 'sometimes|boolean',
        ];
    }
}
