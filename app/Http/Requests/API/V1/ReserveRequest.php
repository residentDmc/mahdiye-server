<?php

namespace App\Http\Requests\API\V1;

use App\Http\Requests\API\V1\MainApiFormRequest;

class ReserveRequest extends MainApiFormRequest
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
            'reserve_id' => [
                'required',
                'exists:reserves,id',
            ]
        ];
    }

    /**
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'reserve_id.required' => 'یکی از رزرو های موجود را انتخاب کنید.',
            'reserve_id.exists' => 'رزرو انتخاب شده موجود نمی باشد.',
        ];
    }

}
