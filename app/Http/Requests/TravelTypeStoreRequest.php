<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TravelTypeStoreRequest extends FormRequest
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
            'name' => 'required|max:100',
            'status' => 'required|exists:statuses,id',            
        ];
    }

    public function messages()
    {
        return [
            'name.required' =>  _trans('validation.Name is required'),
            'name.max' => _trans('validation.Name may not be greater than 100 characters'),
            'status.required' =>  _trans('validation.Status is required'),
        ];
    }
}
