<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:191'],
            'client_id' => ['required'],
            'progress' => ['required'],
            'status' => ['required'],
            'billing_type' => ['required'],
            'estimated_hour' => ['required'],
            'total_rate' => ['required'],
            'start_date' => ['required'],
            'end_date' => ['required'],
            'priority' => ['required'],
            'content' => ['required'],
            'members' => ['required'],

            
        ];
    }
    public function messages()
    {
        return [
            'name.required' => _trans('validation.Name is required'),
            'name.string'  => _trans('validation.Name is not a valid string'),
            'name.max' => _trans('validation.Name may not be greater than 191 characters'),
            'progress.required' => _trans('validation.Progress is required'),
            'status.required' => _trans('validation.Status is required'),
            'client_id.required' => _trans('validation.Client is required'),
            'billing_type.required' => _trans('validation.Billing type is required'),
            'estimated_hour.required' => _trans('validation.Estimated hour is required'),
            'total_rate.required' => _trans('validation.Total Rate is required'),
            'start_date.required' => _trans('validation.Start date is required'),
            'end_date.required' => _trans('validation.End date is required'),
            'priority.required' => _trans('validation.Priority is required'),
            'content.required' => _trans('validation.Description is required'),

        ];
    }
}
