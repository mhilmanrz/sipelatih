<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreActivityParticipantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'activity_id' => 'required|exists:activities,id',
            'user_id' => [
                'required',
                'exists:users,id',
                'unique:activity_participants,user_id,NULL,id,activity_id,' . $this->activity_id,
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.unique' => 'Peserta ini sudah terdaftar di kegiatan ini.',
        ];
    }
}
