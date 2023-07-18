<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DepartmentReqeust extends FormRequest
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
                    'title' => 'required',
                    'status' => 'required',
                ];
            }
            default:
                break;
        }
    }
    public function messages()
    {
        return [
            'title.required' => _trans('validation.Title is required'),
            'status.required' => _trans('validation.Status is required'),
        ];
    }
}
