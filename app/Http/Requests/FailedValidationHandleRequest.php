<?php

namespace App\Http\Requests;

use App\Http\Responses\ApiErrorResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class FailedValidationHandleRequest extends FormRequest
{
    /**
     * @return HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(new ApiErrorResponse('Произошла ошибка валидации', $validator->errors()->all(), 422));
    }
}
