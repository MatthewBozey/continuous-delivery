<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\FailedValidationHandleRequest;

class ResetPasswordRequest extends FailedValidationHandleRequest
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

    public function rules(): array
    {
        return [
            'username' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return ['username' => 'Логин'];
    }
}
