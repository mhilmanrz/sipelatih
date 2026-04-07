<?php

namespace App\Http\Requests\Act;

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

    protected function prepareForValidation()
    {
        $dictionaries = [
            'activity_name_id' => \App\Models\Act\ActivityName::class,
            'activity_type_id' => \App\Models\Act\ActivityType::class,
            'activity_scope_id' => \App\Models\Act\ActivityScope::class,
            'material_type_id' => \App\Models\Act\MaterialType::class,
            'activity_method_id' => \App\Models\Act\ActivityMethod::class,
            'batch_id' => \App\Models\Act\Batch::class,
            'activity_format_id' => \App\Models\Act\ActivityFormat::class,
            'target_participant_id' => \App\Models\Act\TargetParticipant::class,
            'fund_source_id' => \App\Models\Act\FundSource::class,
            'work_unit_id' => \App\Models\User\WorkUnit::class,
        ];

        foreach ($dictionaries as $field => $modelClass) {
            if ($this->has($field) && !empty($this->$field)) {
                // If the input is not numeric OR it's batch_id/work_unit_id (which are now direct text/number inputs in UI)
                if (!is_numeric($this->$field) || $field === 'batch_id' || $field === 'work_unit_id') {

                    // Khusus Unit Pengusul (Work Unit), butuh field 'code' jika baru dibuat
                    if ($field === 'work_unit_id') {
                        // Ambil 3 huruf alphabet/angka pertama jadikan huruf besar (Kapital)
                        $dummyCode = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $this->$field), 0, 3));

                        $record = $modelClass::firstOrCreate(
                            ['name' => $this->$field],
                            ['code' => $dummyCode] // Hanya di set jika data baru dibuat (create)
                        );
                    } else {
                        $record = $modelClass::firstOrCreate(['name' => $this->$field]);
                    }

                    $this->merge([$field => $record->id]);
                }
            }
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
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
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'budget_amount' => 'nullable|numeric',
            'work_unit_id' => 'required|exists:work_units,id',
            'pic_user_id' => 'required|exists:users,id',
            'quota_participant' => 'nullable|integer|min:1',
            'fund_source_id' => 'nullable|exists:fund_sources,id',
            'budget_id' => 'nullable|exists:budgets,id',
        ];
    }
}
