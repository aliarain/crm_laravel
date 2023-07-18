<?php

namespace App\Http\Requests\Configuration;

use Illuminate\Foundation\Http\FormRequest;

class IPAddressReqeust extends FormRequest
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
                    'location' => 'required',
                    'ip_address' => 'required',
                    'status' => 'required',
                ];
            }
            default:
                break;
        }
    }

    public function messages()
    {
        return [
            'location.required' => 'Location is required',
            'ip_address.required' => 'IP Address is required',
            'status.required' => 'Status is required',
        ];
    }
}
