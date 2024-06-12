<?php

namespace App\Http\Requests\WEB;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserInformationRequest extends FormRequest
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
            'first_name' => [
                'required',
                'string',
                'max:250'
            ],
            'last_name' => [
                'required',
                'string',
                'max:250'
            ],
            'postal_code' => [
                'nullable',
                'numeric',
                Rule::unique('users', 'postal_code')->ignore(auth('sanctum')->id())
            ],
            'national_code' => [
                'required',
                'numeric',
                Rule::unique('users', 'national_code')->ignore(auth('sanctum')->id())
            ],
            'certificate_number' => [
                'required',
                'numeric',
                Rule::unique('users', 'certificate_number')->ignore(auth('sanctum')->id())
            ],
            'birthdate' => [
                'required',
                'date_format:Y-m-d'
            ],
            'address' => [
                'required',
                'string'
            ],
            'role' => [
                'required',
                'in:admin,user'
            ],
            'status' => [
                'required',
                'in:active,inactive'
            ]
        ];
    }

    /**
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'first_name.required' => 'نام الزامی است.',
            'first_name.string' => 'نام معتبر نمی باشد.',
            'first_name.max' => 'نام معتبر نمی باشد.',

            'last_name.required' => 'نام خانوادگی الزامی است.',
            'last_name.string' => 'نام خانوادگی معتبر نمی باشد.',
            'last_name.max' => 'نام خانوادگی معتبر نمی باشد.',

            'postal_code.numeric' => 'کد پستی باید با اعداد لاتین وارد شود.',
            'postal_code.unique' => 'کد پستی وارد شده تکراری است.',

            'national_code.required' => 'کد ملی الزامی است.',
            'national_code.numeric' => 'کد ملی باید با اعداد لاتین وارد شود.',
            'national_code.unique' => 'کد ملی وارد شده تکراری است.',

            'certificate_number.required' => 'شماره شناسنامه الزامی است.',
            'certificate_number.numeric' => 'شماره شناسنامه باید با اعداد لاتین وارد شود.',
            'certificate_number.unique' => 'شماره شناسنامه وارد شده تکراری است.',

            'birthdate.required' => 'تاریخ تولد الزامی است.',
            'birthdate.date_format' => 'تاریخ تولد معتبر نمی باشد.',

            'address.required' => 'آدرس الزامی است.',
            'address.string' => 'آدرس معتبر نمی باشد.',

            'role.required' => 'نقش الزامی است.',
            'role.in' => 'نقش انتخاب شده معتبر نمی باشد.',

            'status.required' => 'وضعیت الزامی است.',
            'status.in' => 'وضعیت انتخاب شده معتبر نمی باشد.',
        ];
    }
}
