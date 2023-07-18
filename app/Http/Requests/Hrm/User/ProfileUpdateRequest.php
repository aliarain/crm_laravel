<?php

namespace App\Http\Requests\Hrm\User;

use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
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
        switch ($this->slug) {
            case 'official':
                return [
                    'name' => 'required|max:50',
                    'email' => 'required|email|max:100|unique:users,email,' . $this->user_id,
                    'joining_date' => 'sometimes|date',
                    'employee_type' => 'sometimes|max:30',
                    'employee_id' => 'sometimes|numeric',
                    'manager_id' => 'sometimes|numeric',
                    'department_id' => 'sometimes|numeric',
                    'designation_id' => 'sometimes|numeric',
                    'grade' => 'sometimes|max:30',
                ];
            case 'personal':
            {
                return [
                    'gender' => 'required',
                    'phone' => 'required|numeric|unique:users,phone,' . $this->user_id,
                    // 'phone' => 'required|unique:users,phone,' . $this->user_id,
                    'birth_date' => 'sometimes|date',
                    'address' => 'sometimes|max:190',
                    'nationality' => 'sometimes|max:30',
                    'nid_card_number' => 'sometimes|max:17',
                    'passport_number' => 'sometimes|max:50',
                    'passport_file' => 'sometimes|image',
                    'nid_file' => 'sometimes|image',
                    'avatar' => 'sometimes|image',
                    'blood_group' => 'required'
                ];
            }
            case 'financial':
            {
                return [
                    'tin' => 'sometimes|max:50',
                    'bank_name' => 'sometimes|max:50',
                    'bank_account' => 'sometimes|max:30'
                ];
            }
            case 'emergency':
            {
                return [
                    'emergency_name' => 'sometimes|max:50',
                    'emergency_mobile_number' => 'required|numeric',
                    'emergency_mobile_relationship' => 'sometimes|max:30'
                ];
            }
            case 'salary':
            {
                return [
                    'basic_salary' => 'sometimes|max:11',
                ];
            }
            case 'contract':
            {
                return [
                    'basic_salary' => 'sometimes|max:11',
                    'contract_start_date' => 'sometimes|date',
                    'contract_end_date' => 'sometimes|date',
                    'salary_type' => 'sometimes|max:30',
                ];
            }
            case 'security':
            {
                return [
                    'old_password' => 'required',
                    'password' => 'required|confirmed|min:6',
                ];
            }
            case 'company':
            {
                return [
                    'company_name' => 'required',
                    'email' => 'required',
                    'phone' => 'required',
                    'total_employee' => 'required',
                    'business_type' => 'sometimes|max:50',
                    'trade_licence_number' => 'sometimes|max:50',
                ];
            }
            default:
                break;
        }
    }

    public function messages()
    {
        return [
            'phone.numeric' => 'Phone number should be a numeric number.',
            'gender.required' => 'Gender is required.',
            'blood_group.required' => 'Blood group is required.',
            
        ];
    }
}
