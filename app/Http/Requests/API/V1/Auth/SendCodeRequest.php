<?php

namespace App\Http\Requests\API\V1\Auth;

use App\Http\Requests\API\V1\MainApiFormRequest;

class SendCodeRequest extends MainApiFormRequest
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
                'numeric',
                'digits:11'
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
            'mobile.numeric' => 'شماره موبایل را با اعداد لاتین وارد کنید.',
            'mobile.digits' => 'شماره موبایل باید 11 رقمی باشد.',
        ];
    }
}
