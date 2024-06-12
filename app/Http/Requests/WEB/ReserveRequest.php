<?php

namespace App\Http\Requests\WEB;

use Illuminate\Foundation\Http\FormRequest;

class ReserveRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'start_time' => $this->to_english_numbers($this->input('start_time')),
            'end_time' => $this->to_english_numbers($this->input('end_time')),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'date' => [
                'required',
                'date_format:Y-m-d',
            ],
            'start_time' => [
                'required',
                'date_format:H:i',
            ],
            'end_time' => [
                'required',
                'date_format:H:i',
            ],
            'capacity' => [
                'required',
                'numeric',
                'min:1'
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
            'date.required' => 'تاریخ الزامی است.',
            'date.date_format' => 'تاریخ معتبر نمی باشد.',
            'start_time.required' => 'زمان شروع الزامی است.',
            'start_time.date_format' => 'زمان شروع معتبر نمی باشد.',
            'end_time.required' => 'زمان پایان الزامی است.',
            'end_time.date_format' => 'زمان پایان معتبر نمی باشد.',
            'status.required' => 'وضعیت الزامی است.',
            'status.in' => 'وضعیت معتبر نمی باشد.',
            'capacity.required' => 'ظرفیت الزامی است.',
            'capacity.numeric' => 'ظرفیت معتبر نمی باشد.',
            'capacity.min' => 'حداقل ظرفیت مجاز 1 می باشد.',
        ];
    }

    public function to_english_numbers(String $string): String {
        $persianDigits1 = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $persianDigits2 = ['٩', '٨', '٧', '٦', '٥', '٤', '٣', '٢', '١', '٠'];
        $allPersianDigits = array_merge($persianDigits1, $persianDigits2);
        $replaces = [...range(0, 9), ...range(0, 9)];

        $result = str_replace($allPersianDigits, $replaces , $string);
        $exploded = explode(':', $result);
        if (strlen((string)$exploded[0]) == 1)
            $h = "0" . (string)$exploded[0];
        else
            $h = $exploded[0];

        if (strlen((string)$exploded[1]) == 1)
            $m = "0" . (string)$exploded[1];
        else
            $m = $exploded[1];
        return (string)$h . ":" . (string)$m;
    }
}
