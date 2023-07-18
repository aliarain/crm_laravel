<?php

namespace App\Http\Requests\Finance;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDepositRequest extends FormRequest
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
            'account' => 'required|max:191',
            'category' => 'required:max:191',
            'date' => 'required|max:191',
            'amount' => 'required|max:191',
            'payment_method_id' => 'required|max:191',
            'ref' => 'nullable|max:191',
            'description' => 'required|max:391',
            'attachment'  => 'nullable|mimes:jpeg,png,jpg,pdf,doc|max:2048',
        ];
    }
    public function messages()
    {
        return [
            'account.required' => _trans('validation.Account is required'),
            'category.required' => _trans('validation.Category is required'),
            'date.required' => _trans('validation.Date is required'),
            'amount.required' => _trans('validation.Amount is required'),
            'payment_method_id.required' => _trans('validation.Payment Method is required'),
            'description.required' => _trans('validation.Description is required'),
            'attachment.mimes' => _trans('validation.Attachment must be a file of type: jpeg, png, jpg, pdf, doc.'),
            'ref.max' => _trans('validation.Ref must be less than 191 characters.'),
        ];
    }
}
