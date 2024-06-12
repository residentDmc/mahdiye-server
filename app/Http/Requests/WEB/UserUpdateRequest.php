<?php

namespace App\Http\Requests\WEB;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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
            'first_name' => 'required|string:150',
            'last_name' => 'required|string:150',
            'father_name' => 'required|string:150',
            'mobile' => 'required|numeric|digits:11',
            'birthdate' => 'required',
            'postal_code' => 'required|numeric',
            'national_code' => 'required|numeric|digits:10',
            'certificate_number' => 'required|numeric',
            'status' => 'required|in:active,inactive',
            'role' => 'required|in:admin,user',
            'permissions' => 'nullable|exists:permissions,name',
            'address' => 'nullable|string',
        ];
    }


    public function attributes()
    {
        return [
            'first_name' => 'نام',
            'last_name' => 'نام خانوادگی',
            'father_name' => 'نام پدر',
            'mobile' => 'موبایل',
            'birthdate' => 'تاریخ تولد',
            'postal_code' => 'کد پستی',
            'national_code' => 'کد ملی',
            'certificate_number' => 'شماره شناسنامه',
            'status' => 'وضعیت',
            'role' => 'نقش',
            'permissions' => 'دسترسی های ادمین',
            'address' => 'آدرس'
        ];
    }
}
