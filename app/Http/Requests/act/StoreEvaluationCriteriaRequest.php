<?php

namespace App\Http\Requests\Act;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreEvaluationCriteriaRequest extends FormRequest
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
            'code' => ['required', 'string', 'max:50', 'unique:evaluation_criteria,code'],
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:rating,isian,file'],
            'evaluation_type' => ['required', 'integer', 'in:1,3'],
            'form_type' => ['nullable', 'required_if:evaluation_type,1', 'in:speaker,activity'],
            'evaluation_category_id' => ['nullable', 'exists:evaluation_categories,id'],
            'order' => ['integer', 'min:0'],
        ];
    }
}
