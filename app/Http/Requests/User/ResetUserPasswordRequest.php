<?php

namespace App\Http\Requests\User;

use App\Http\Requests\FailedValidationHandleRequest;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rules\Password;

class ResetUserPasswordRequest extends FailedValidationHandleRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'newPassword' => [
                'required',
                'string',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
                'confirmed',
            ],
            'user_id' => ['required', 'integer', 'exists:App\Models\User,user_id'],
        ];
    }

    public function attributes(): array
    {
        return [
            'newPassword' => 'Пароль',
            'user_id' => 'Уникальный идентификатор пользователя',
        ];
    }
}
