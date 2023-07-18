<?php

namespace App\Http\Requests\Performance;

use Illuminate\Foundation\Http\FormRequest;

class AppraisalRequest extends FormRequest
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
                    'title' => 'required|max:255',
                    'user_id' => 'required',
                    'date' => 'required',
                    'remarks' => 'max:600',
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
            'title.required' => _trans('validation.Title is required'),
            'title.max' => _trans('validation.Title  is maximum 255 character'),
            'user_id.required' => _trans('validation.Employee is required'),
            'date.required' => _trans('validation.Date is required'),
            'remarks.required' => _trans('validation.Remarks is maximum 600 character'),
            'rating.required' => _trans('validation.Rating is required')
        ];
    }
}
