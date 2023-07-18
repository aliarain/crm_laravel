<?php

namespace App\Http\Requests\Setting;

use Illuminate\Foundation\Http\FormRequest;

class TeamMemberRequest extends FormRequest
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
            'name' => 'required|max:255',
            'designation' => 'required|max:255',
            'content' => 'required|max:255',
            'attachment' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => _trans('validation.Name is required'),
            'name.max' => _trans('validation.Name is not 255 characters'),
            'designation.required' => _trans('validation.Designation is required'),
            'designation.max' => _trans('validation.Designation is not 255 characters'),
            'attachment.required' => _trans('validation.Attachment is required'),
            'attachment.max' => _trans('validation.Attachment is not more than 2048KB.'),
            'attachment.mimes' => _trans('validation.Attachment is supported only with mime type jpeg,png,jpg,gif,svg'),
            'content.required' => _trans('validation.Description is required'),
            'content.max' => _trans('validation.Description is not more than 500 characters'),
            'status.required' => _trans('validation.Status is required'),
        ];
    }
}
