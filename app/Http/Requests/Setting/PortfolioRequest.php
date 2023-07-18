<?php

namespace App\Http\Requests\Setting;

use Illuminate\Foundation\Http\FormRequest;

class PortfolioRequest extends FormRequest
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
            'title' => 'required|max:255',
            'content' => 'required|max:800',
            'status' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => _trans('validation.Title is required'),
            'title.max' => _trans('validation.Title is not 255 characters'),
            'content.required' => _trans('validation.Content is required'),
            'content.max' => _trans('validation.Content is not 800 characters'),
            'status.required' => _trans('validation.Status is required'),
        ];
    }
}
