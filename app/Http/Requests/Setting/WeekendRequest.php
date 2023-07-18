<?php

namespace App\Http\Requests\Setting;

use Illuminate\Foundation\Http\FormRequest;

class WeekendRequest extends FormRequest
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
            'is_weekend' => 'required',
            'status' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => _trans('validation.Name is required'),
            'name.max' => _trans('validation.name is not 255 characters'),
            'is_weekend.required' => _trans('validation.Is weekend is required'),
            'status.required' => _trans('validation.Status is required'),
        ];
    }
}
