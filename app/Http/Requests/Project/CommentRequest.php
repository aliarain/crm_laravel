<?php

namespace App\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
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
            'comment' => 'required|max:2000',
        ];
    }

    public function messages()
    {
        return [
            'comment.required' => _trans('validation.Comment is required'),
            'comment.max' => _trans('validation.Comment must be less than 2000 characters'),
        ];
    }
}
