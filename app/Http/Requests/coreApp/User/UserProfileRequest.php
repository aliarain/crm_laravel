<?php

namespace App\Http\Requests\coreApp\User;

use Illuminate\Foundation\Http\FormRequest;

class UserProfileRequest extends FormRequest
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
            'name' => 'required|max:250',
            'phone' => 'required|unique:users,phone,' . \request()->get('id'),
            'email' => 'required|unique:users,email,' . \request()->get('id'),
            'avatar' => 'sometimes|image|mimes:jpeg,png,jpg|max:25000'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => _trans('common.Name is required'),
            'name.max' => _trans('common.Name may not be greater than 250 characters'),
            'phone.required' => _trans('common.Phone is required'),
            'phone.unique' => _trans('common.Phone is unique'),
            'email.required' => _trans('common.Email is required'),
            'email.unique' => _trans('common.Email is unique'),
            'avatar.mimes' => _trans('validation.Attachment must be a file of type: jpeg, png, jpg.'),
        ];
    }
}
