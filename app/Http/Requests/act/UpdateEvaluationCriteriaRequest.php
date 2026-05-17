<?php

namespace App\Http\Requests\Act;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateEvaluationCriteriaRequest extends FormRequest
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
        $id = $this->route('evaluation_criteria') ?? $this->route('evaluation_criterium') ?? $this->route('id');

        return [
            'code' => ['required', 'string', 'max:50', 'unique:evaluation_criteria,code,'.$id],
            'name' => ['required', 'string', 'max:255'],
            'is_fillable' => ['boolean'],
            'type' => ['required_if:is_fillable,true', 'in:string,number'],
            'evaluation_type' => ['required', 'integer', 'in:1,2,3'],
            'order' => ['integer', 'min:0'],
        ];
    }
}
