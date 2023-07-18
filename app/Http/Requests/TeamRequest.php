<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TeamRequest extends FormRequest
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
            'team_lead_id' => ['required'],
            'status_id' => ['required'],
        ];
    }


    public function messages()
    {
        return [
            'name.required' =>  _trans('validation.Name is required'),
            'name.string'  => _trans('validation.Name is not a valid string'),
            'name.max' => _trans('validation.Name may not be greater than 255 characters'),
            'status.required' =>  _trans('validation.Status is required'),
            'team_lead_id.required' => _trans('validation.Team lead is required'),
        ];
    }
}
