<?php

namespace App\Http\Requests\Performance;

use Illuminate\Foundation\Http\FormRequest;

class GoalRequest extends FormRequest
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
                    'subject' => 'required|max:255',
                    'goal_type_id' => 'required',
                    'start_date' => 'required',
                    'end_date' => 'required',
                    'target' => 'required',
                    'start_date' => 'required',
                    'end_date' => 'required',
                    'description' => 'required|max:600',
                    
                ];
            }
            default:
                break;
        }
    }

    public function messages()
    {
        return [
            'subject.required' => _trans('validation.Subject is required'),
            'subject.max' => _trans('validation.Subject is maximum 255 character'),
            'goal_type_id.required' => _trans('validation.Goal type is required'),
            'start_date.required' => _trans('validation.Start date is required'),
            'end_date.required' => _trans('validation.End date is required'),
            'description.required' => _trans('validation.Description is required'),
            'target.required' => _trans('validation.Target is required'),
           


        ];
    }
}
