<?php

namespace App\Http\Requests\Performance;

use Illuminate\Foundation\Http\FormRequest;

class IndicatorRequest extends FormRequest
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
                    'department_id' => 'required',
                    'designation_id' => 'required',
                    'shift_id' => 'required',
                    'rating' => 'required',
                ];
            }
            default:
                break;
        }
    }

    public function messages()
    {
        return [
            'title.required' => _trans('common.Title is required'),
            'department_id.required' => _trans('common.DepartmentId is required'),
            'designation_id.required' => _trans('common.DesignationId is required'),
            'shift.required' => _trans('common.Shift is required'),
            'rating.required' => _trans('common.Rating is required')
        ];
    }
}
