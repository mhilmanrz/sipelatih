<?php

namespace App\Http\Requests\Act;

use App\Models\Act\FundSource;
use Illuminate\Contracts\Validation\ValidationRule;
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
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        if ($this->fund_source_id && ! is_numeric($this->fund_source_id)) {
            $fundSource = FundSource::firstOrCreate([
                'name' => $this->fund_source_id,
            ]);

            $this->merge([
                'fund_source_id' => $fundSource->id,
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'activity_name_id' => 'nullable|exists:activity_names,id',
            'date' => 'nullable|date',
            'reference_number' => 'nullable|string',
            'activity_type_id' => 'required|exists:activity_types,id',
            'activity_category_id' => 'required|exists:activity_categories,id',
            'activity_scope_id' => 'required|exists:activity_scopes,id',
            'material_type_id' => 'required|exists:material_types,id',
            'activity_method_id' => 'required|exists:activity_methods,id',
            'batch_id' => 'required|exists:batches,id',
            'activity_format_id' => 'required|exists:activity_formats,id',
            'collaboration_inst' => 'nullable|string|max:255',
            'tempat' => 'nullable|string|max:255',
            'tujuan' => 'nullable|string',
            'justifikasi' => 'nullable|string',
            'target_kompetensi' => 'nullable|string',
            'fund_source_id' => 'nullable|exists:fund_sources,id',
            'target_participant_id' => 'nullable|exists:target_participants,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
            'budget_amount' => 'nullable|numeric',
            'work_unit_id' => 'nullable|exists:work_units,id',
            'pic_user_id' => 'nullable|exists:users,id',
            'organizer_pic_id' => 'nullable|exists:users,id',
            'quota_participant' => 'nullable|integer|min:1',
            'budget_id' => 'nullable|exists:budgets,id',
        ];
    }
}
