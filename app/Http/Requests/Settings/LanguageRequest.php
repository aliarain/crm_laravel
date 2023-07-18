<?php

namespace App\Http\Requests\Settings;

use Illuminate\Foundation\Http\FormRequest;

class LanguageRequest extends FormRequest
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
        switch ($this->method()) {
            case 'GET':
            case 'DELETE':
            {
                return [];
            }
            case 'POST' || 'PATCH':
            {
                return [
                    'name' => 'required',
                    'status' => 'required',
                    'rtl' => 'required',
                    'native' => 'required',
                    'code' => 'required'
                ];
            }
            default:
                break;
        }
    }
    public function messages()
    {
        return [
            'name.required' => _trans('common.Name is required'),
            'status.required' => _trans('common.Status is required'),
            'rtl.required' => _trans('common.RTL is required'),
            'native.required' => _trans('common.Native is required'),          
            'code.required' => _trans('common.Code is required'),  
        ];
    }
}
