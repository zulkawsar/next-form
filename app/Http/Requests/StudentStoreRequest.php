<?php

namespace App\Http\Requests;

use App\Models\Student;
use Illuminate\Validation\Rule;
use App\Traits\CustomFieldValidation;
use Illuminate\Foundation\Http\FormRequest;

class StudentStoreRequest extends FormRequest
{
    use CustomFieldValidation;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $validate =  [
            'class' => ['required', 'string', 'max:255'],
            'name'  =>  ['required', 'max:255'],
            'phone' => ['required', 'regex:/^(\+?880|0)1[3456789][0-9]{8}$/'],
            'email' => ['email', 'max:255', Rule::unique(Student::class)],
            'status'=> ['required', Rule::in([Student::STDUNCONFIRMED, Student::STDADMITTED, Student::STDTERMINATED])],
            
        ];
        return array_merge($validate, $this->generateValidateRules());
    }

    /**
     * Custom message for validation
     *
     * @return array
     */
    public function messages()
    {
        return [
            'required' => "Field is required",
        ];
    }
}
