<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StudentRequest extends FormRequest
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
            // 'student_id' => 'max:11',
            'name' => 'required|alpha_spaces',
            'father_name' => 'alpha_spaces',
            'nrc_number' => 'required|max:40',
            'phone_no' => 'regex:/(^[0-9]{9,11}$)/u',
            'email' => 'required|email',
            'gender' => 'required|between:1,2',
            'date_of_birth' => 'date',
            'avatar' => 'max:255',
            'address' => 'max:500',
            'career_path' => 'between:1,2',
            'created_emp' => 'required|max:11',
            'updated_emp' => 'required|max:11',
            // 'skill' => '',
            // 'skill.*' => 'between:1,6',
        ];
    }

    public function messages()
    {
        return [
            'name.alpha_spaces' => 'Name must contain only alphabets and spaces.',
            'father_name.alpha_spaces' => 'Fathername must contain only alphabets and spaces.',
            'nrc_number.required' => 'NRC number is required.',
            'phone_number.required' => 'Phone number is required.',
            'phone_number.max' => 'Phone number is required.',
            'phone_number.numeric' => 'Phone number is required.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([$validator->errors()],422));
    }
}
