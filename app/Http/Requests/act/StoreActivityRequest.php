<?php

namespace App\Http\Requests\Act;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreActivityRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'activity_name_id' => 'required|exists:activity_names,id',
            'date' => 'nullable|date',
            'reference_number' => 'nullable|string|max:255',
            'activity_type_id' => 'required|exists:activity_types,id',
            'activity_scope_id' => 'required|exists:activity_scopes,id',
            'material_type_id' => 'required|exists:material_types,id',
            'activity_method_id' => 'required|exists:activity_methods,id',
            'batch_id' => 'required|exists:batches,id',
            'activity_format_id' => 'required|exists:activity_formats,id',
            'collaboration_inst' => 'nullable|string|max:255',
            'target_participant_id' => 'nullable|exists:target_participants,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'budget_amount' => 'nullable|numeric',
            'work_unit_id' => 'nullable|exists:work_units,id',
            'pic_user_id' => 'nullable|exists:users,id',
            'quota_participant' => 'nullable|integer|min:1',
            'budget_id' => 'nullable|exists:budgets,id',
        ];
    }
}
