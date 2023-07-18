<?php

namespace App\Http\Requests\coreApp\User;

use Illuminate\Foundation\Http\FormRequest;

class UserProfileThumbnailRequest extends FormRequest
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
            'profile_picture' => 'required|mimes:jpeg,jpg,png|max:1000',
        ];
    }
    public function messages()
    {
        return [
            'profile_picture.required' => _trans('common.Profile picture is required'),
            'profile_picture.mimes' => _trans('validation.Attachment must be a file of type: jpeg, png, jpg.'),
        ];
    }
}
