<?php

namespace App\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;

class FileRequest extends FormRequest
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
        if(@$this->attach_file && pathinfo(@$this->attach_file->getClientOriginalName(), PATHINFO_EXTENSION) =='sql'){
            return [
                'subject' => 'required|max:200',
                'attach_file' => 'required|max:20048',
            ];
        }else {
            return [
                'subject' => 'required:max:200',
                'attach_file' => 'required|mimes:jpeg,png,jpg,gif,svg,pdf,csv,doc,docx|max:20048',
            ];
        }
    }

    public function messages()
    {
        return [
            'subject.required' => 'Subject is required',
            'subject.max' => 'Subject must be less than 200 characters',
            'attach_file.required' => 'File is required',
            'attach_file.mimes' => 'File must be a file of type: jpeg, png, jpg, gif, svg, pdf, csv, doc, docx.',
            'attach_file.max' => 'File may not be greater than 20 megabytes.',
        ];
    }
}
