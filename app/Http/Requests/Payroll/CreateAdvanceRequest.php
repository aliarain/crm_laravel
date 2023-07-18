<?php

namespace App\Http\Requests\Payroll;

use Illuminate\Foundation\Http\FormRequest;

class CreateAdvanceRequest extends FormRequest
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
            'user_id' => 'required | max:191',
            'month' => 'required',
            'advance_type' => 'required',
            'amount' => 'required',
            'recovery_mode' => 'required',
            'recovery_cycle' => 'required',
            'installment_amount' => 'required',
            'recover_from' => 'required',
            'reason' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'user_id.required' => _trans('validation.UserId is required'),
            'month.required' => _trans('validation.Month is required'),
            'advance_type.required' => _trans('validation.AdvanceType is required'),
            'amount.required' => _trans('validation.Amount is required'),
            'recovery_mode.required' => _trans('validation.RecoveryMode is required'),
            'recovery_cycle.required' => _trans('validation.RecoveryCycle is required'),
            'installment_amount.required' => _trans('validation.InstallmentAmount is required'),
            'recover_from.required' => _trans('validation.RecoverFrom is required'),
            'reason.required' => _trans('validation.Reason is required'),
        ];
    }
}
