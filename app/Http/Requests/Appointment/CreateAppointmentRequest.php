<?php

namespace App\Http\Requests\Appointment;

use Illuminate\Foundation\Http\FormRequest;

class CreateAppointmentRequest extends FormRequest
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
            'title' => 'required|max:191',
            'description' => 'required|max:800',
            'location' => 'required|max:255',
            'date' => 'required',
            'appoinment_with' => 'required',
            'appoinment_start_at' => 'required',
            'appoinment_end_at' => 'required',
        ];
    }
    public function messages()
    {
        return [
                'title.required' => _trans('Title is required'),
                'title.max' => _trans('Title is too long than 191 character'),
                'location.required' => _trans('Location is required'),
                'location.max' => _trans('Location is too long than 191 character'),
                'date.required' => _trans('Date schedule is required'),
                'description.required' => _trans('Description is required'),
                'description.max' => _trans('Description is not more than 800 characters'),             
                'appoinment_start_at.required' => _trans('Start time is required'),
                'appoinment_end_at.required' => _trans('End time is required'),
                'appoinment_with.required' => _trans('Appointment with is required'),

            ];
    }
}
