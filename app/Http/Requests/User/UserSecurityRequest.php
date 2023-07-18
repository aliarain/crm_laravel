<?php

namespace App\Http\Requests\User;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UserSecurityRequest extends FormRequest
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
            'email' => "required|email|max:255",
            'email' => Rule::unique('users')->ignore($this->user_id),
            'old_password' => 'required|max:255',
            'password' =>'required|different:old_password',
            'password' => 'required|required_with:password_confirmation|same:password_confirmation|min:8|confirmed',
            'password_confirmation' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'email.required' => _trans('validation.Email is required'),
            'email.email'  => _trans('validation.Provide Right Email address'),
            'email.unique' => _trans('validation.Provide Unique Email address'),
            'old_password.required' => _trans('validation.Old password is required'),
            'email.max' => _trans('validation.Email is not allowed more than 255 characters'),
            'password.required' => _trans('validation.Password is required'),
            'password.min' => _trans('validation.Password required minimum 8 character'),
            'password.same' => _trans('validation.Password & confirmation password must be same'),
            'password_confirmation.required' => _trans('validation.Password confirmation is required'),
            'password.different' => _trans('validation.Password & old password are same')
        ];
    }
}
