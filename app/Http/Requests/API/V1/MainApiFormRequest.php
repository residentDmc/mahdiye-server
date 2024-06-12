<?php

namespace App\Http\Requests\API\V1;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class MainApiFormRequest extends FormRequest
{

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
                'result' => [
                    'errors' => call_user_func_array('array_merge', array_values($validator->errors()->toArray()))
                ]
            ], 400)
        );
    }
}
