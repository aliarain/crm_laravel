<?php

namespace App\Http\Requests\Hrm\Leave;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LeaveTypeRequest extends FormRequest
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
                    'name' => 'required|max:50',
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
            'name.required' => _trans('common.Name is required'),
            'name.max' => _trans('common.Name may not be greater than 50 characters'),
            'status.required' => _trans('common.Status is required'),
        ];
    }
}
