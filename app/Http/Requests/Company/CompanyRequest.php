<?php

namespace App\Http\Requests\Company;

use Illuminate\Foundation\Http\FormRequest;

class CompanyRequest extends FormRequest
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
            case 'POST':
            {
                return [
                    'name' => 'required',
                    'company_name' => 'required',
                    'email' => 'required|email|unique:companies,email',
                    'email' => 'required|email|unique:users,email',
                    'phone' => 'required|unique:companies,phone',
                    'phone' => 'required|unique:users,phone',
                    'total_employee' => 'required',
                    'business_type' => 'required',
                    'trade_licence_number' => 'required',
                    'status_id' => 'required',
                ];
            }
            case 'PATCH':
            {
                return [
                    'name' => 'required|max:255',
                    'company_name' => 'required|max:255',
                    'email' => 'required|email|unique:companies,email,' . $this->company->id,
                    'email' => 'required|email|unique:users,email,' . $this->company->user->id,
                    'phone' => 'required|unique:companies,phone,' . $this->company->id,
                    'phone' => 'required|unique:users,phone,' . $this->company->user->id,
                    'total_employee' => 'required',
                    'business_type' => 'required',
                    'trade_licence_number' => 'required',
                    'status_id' => 'required',
                ];
            }
            default:
                break;
        }
    }


    public function messages()
    {
        return [
            'name.required' => _trans('validation.Name is required'),
            'name.max' => _trans('common.Name may not be greater than 250 characters'),
            'company_name.required' => _trans('validation.Company name is required'),
            'company_name.max' => _trans('common.Company name may not be greater than 250 characters'),
            'email.required' => _trans('validation.Email is required'),
            'email.unique' => _trans('validation.Email is unique'),
            'phone.required' => _trans('validation.Phone is required'),
            'phone.unique' => _trans('validation.Phone is unique'),
            'total_employee.required' => _trans('validation.Total employee is required'),
            'business_type.required' => _trans('validation.Business type is required'),
            'trade_licence_number.required' => _trans('validation.Trade license number is required'),
            'status_id.required' => _trans('validation.Status is required'),            
        ];
    }
}
