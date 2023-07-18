<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NoticeReqeust extends FormRequest
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
                    'department_id' => 'required', 
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
            'subject.max' => _trans('validation.Subject is maximum 255 characters'),
            'department_id.required' => _trans('validation.Department is required'),
        ];
    } 
}
