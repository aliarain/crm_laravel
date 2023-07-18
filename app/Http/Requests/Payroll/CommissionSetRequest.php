<?php

namespace App\Http\Requests\Payroll;

use Illuminate\Foundation\Http\FormRequest;

class CommissionSetRequest extends FormRequest
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
            case 'DELETE': {
                    return [];
                }
            case 'POST' || 'PATCH': {
                    return [
                        'status' => 'required',
                        'amount' => 'required|numeric',
                        'set_up_id' => 'required|numeric',
                        'type' => 'required|numeric',
                    ];
                }
            default:
                break;
        }
    }
    public function messages()
    {
        return [
            'status.required' => _trans('common.Status is required'),
            'status.numeric' => _trans('common.Status is numeric'),
            
            'amount.required' => _trans('common.Amount is required'),
            'amount.numeric' => _trans('common.Amount is numeric'),

            'set_up_id.required' => _trans('common.Setup id is required'),
            'set_up_id.numeric' => _trans('common.Setup id is numeric'),

            'type.required' => _trans('common.Type is required'),
            'type.numeric' => _trans('common.Type is numeric'),
        ];
    }
}
