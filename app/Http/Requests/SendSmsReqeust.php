<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendSmsReqeust extends FormRequest
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
                    'department_id' => 'required',
                    'message' => 'required', 
                ];
            }
            default:
                break;
        }
    }
    public function messages()
    {
        return [
            'department_id.required' => _trans('validation.Department is required'),
            'message.required' => _trans('validation.Message is required')
        ];
    }
}
