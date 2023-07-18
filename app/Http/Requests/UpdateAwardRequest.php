<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAwardRequest extends FormRequest
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
            'attachment' => 'nullable|mimes:jpeg,png,jpg|max:2048',
            'content' => 'required',
        ];
    }
    public function messages()
    {
        return [
            'user_id.required' => _trans('validation.User ID is required'),
            'award_type.required' => _trans('validation.Award type is required'),
            'gift.required' => _trans('validation.Gift is required'),
            'date.required' => _trans('validation.Date is required'),
            'award_info.required' => _trans('validation.Award information is required'),
            'status.required' => _trans('validation.Status is required'),
            'amount.required' => _trans('validation.Amount is required'),
            'attachment.required' => _trans('validation.Attachment is required'),
            'attachment.mimes' => _trans('validation.Attachment must be a file of type: jpeg, png, jpg, gif.'),
            'content.required' => _trans('validation.Description is required'),
        ];
    }
}
