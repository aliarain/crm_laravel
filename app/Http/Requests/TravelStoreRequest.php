<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TravelStoreRequest extends FormRequest
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
            'travel_type' => 'required',
            'motive' => 'required',
            'place' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'mode' => 'required',
            'expect_amount' => 'required',
            'actual_amount' => 'required',
            'attachment' => 'sometimes|mimes:jpeg,png,jpg,gif|max:2048',
            'content' => 'sometimes|required',
        ];
    }

    public function messages()
    {
        return [
            'user_id.required' => _trans('validation.User ID is required'),
            'travel_type.required' => _trans('validation.Travel type is required'),
            'motive.required' => _trans('validation.Motive is required'),
            'place.required' => _trans('validation.Place is required'),
            'start_date.required' => _trans('validation.Start date is required'),
            'end_date.required' => _trans('validation.End date is required'),
            'mode.required' => _trans('validation.Mode is required'),
            'expect_amount.required' => _trans('validation.Expect amount is required'),
            'actual_amount.required' => _trans('validation.Actual amount is required'),
            'attachment.required' => _trans('validation.Attachment is required'),
            'attachment.mimes' => _trans('validation.Attachment must be a file of type: jpeg, png, jpg, gif.'),
            'content.required' => _trans('validation.Description is required'),
        ];
    }
}
