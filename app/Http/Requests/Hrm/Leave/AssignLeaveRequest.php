<?php

namespace App\Http\Requests\Hrm\Leave;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AssignLeaveRequest extends FormRequest
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
                    'days' => 'required|numeric',
                    'department_id' => 'required',
                    'type_id' => 'required',
                    'status_id' => 'required',
                ];
            }
            default:
                break;
        }
    }
    public function messages()
    {
        return [
            'days.required' => _trans('validation.Days is required'),
            'days.numeric' => _trans('validation.Days is not a number'),
            'department_id.required' => _trans('validation.Department is required'),
            'type_id.required' => _trans('validation.Type is required'),
            'status.required' => _trans('validation.Status is required'),
        ];
    }
}
