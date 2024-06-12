<?php

namespace App\Http\Requests\WEB;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'mobile' => [
                'required',
                'exists:users,mobile'
            ],
            'password' => [
                'required',
                'string'
            ]
        ];
    }

    /**
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'mobile.required' => 'شماره موبایل الزامی است.',
            'mobile.exists' => 'شماره موبایل معتبر نمی باشد.',
            'password.required' => 'پسورد الزامی است.',
            'password.string' => 'پسورد معتبر نمی باشد.',
        ];
    }
}
