<?php

namespace App\Http\Requests\Act;

use Illuminate\Foundation\Http\FormRequest;

class UpdateActivityRequest extends FormRequest
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
            'date' => 'nullable|date',
            'reference_number' => 'nullable|string|max:255',
            'activity_type_id' => 'nullable|exists:activity_types,id',
            'activity_scope_id' => 'nullable|exists:activity_scopes,id',
            'material_type_id' => 'nullable|exists:material_types,id',
            'activity_method_id' => 'nullable|exists:activity_methods,id',
            'batch_id' => 'nullable|exists:batches,id',
            'activity_format_id' => 'nullable|exists:activity_formats,id',
            'collaboration_inst' => 'nullable|string|max:255',
            'target_participant_id' => 'nullable|exists:targer_participants,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'budget_amount' => 'nullable|numeric',
            'work_unit_id' => 'nullable|exists:work_units,id',
            'user_id' => 'nullable|exists:users,id',
        ];
    }
}
