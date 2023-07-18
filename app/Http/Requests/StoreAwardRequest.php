<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAwardRequest extends FormRequest
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
                'user_id' => 'required',
                'award_type' => 'required',
                'gift' => 'required',
                'date' => 'required',
                'status' => 'required',
                'award_info' => 'required',
                'amount' => 'required',
                'attachment' => 'required|mimes:jpeg,png,jpg|max:2048',
                'content' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'user_id.required' => _trans('validation.User Id is required'),
            'award_type.required' => _trans('validation.Award Type is required'),
            'gift.required' => _trans('validation.Gift is required'),
            'date.required' => _trans('validation.Date is required'),
            'status.required' => _trans('validation.Status is required'),
            'award_info.required' => _trans('validation.Award Information is required'),
            'amount.required' => _trans('validation.Amount is required'),
            'attachment.required' => _trans('validation.Attachment is required'),
            'attachment.mimes' => _trans('validation.Attachment must be a file of type: jpeg, png, jpg.'),
            'content.required' => _trans('validation.Description is required'),

        ];
    }
}
