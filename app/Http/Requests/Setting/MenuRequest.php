<?php

namespace App\Http\Requests\Setting;

use Illuminate\Foundation\Http\FormRequest;

class MenuRequest extends FormRequest
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
            'type' => 'required|max:255',
            'url' => 'required|max:255',
            'status' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => _trans('validation.Name is required'),
            'type.required' => _trans('validation.Type is required'),
            'url.required' => _trans('validation.URL is required'),
            'status.required' => _trans('validation.Status is required'),
        ];
    }
}
