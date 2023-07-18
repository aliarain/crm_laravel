<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IncomeExpenseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'expense_date' => 'required|max:255',
            'supplier_id' => 'required|string|max:255',
            'type' => 'nullable|string|max:255',
            'category_id' => 'nullable|string|max:255',

        ];
    }

    public function messages()
    {
        return [
            'expense_date.required' => 'Please enter date field',
            'supplier_id.required' => 'Please Select supplier field',
            'category_id.required' => 'Please Select category field',
            'amount.required' => 'Please enter amount field',

        ];
    }
}
