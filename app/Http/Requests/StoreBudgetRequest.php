<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBudgetRequest extends FormRequest
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
            'budget_category_id' => 'required|integer|exists:budget_categories,id',
            'rkkal_code' => 'required|string|max:255',
            'submark' => 'required|string|max:255',
            'total_amount' => 'required|integer|min:0',
            'remaining_amount' => 'required|integer|min:0',
        ];
    }
}
