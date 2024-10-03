<?php

namespace App\Http\Requests\User;

use App\Http\Requests\FailedValidationHandleRequest;
use Illuminate\Validation\Rules\Password;

class UserCreateRequest extends FailedValidationHandleRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'username' => [
                'required',
                'string',
                "unique:App\Models\User,username",
            ],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)->letters()->mixedCase()->numbers()->symbols()->uncompromised(),
            ],
            'email' => [
                'required',
                'email',
                "unique:App\Models\User,email",
            ],
            'first_name' => [
                'required',
                'string',
            ],
            'last_name' => [
                'required',
                'string',
            ],
            'patronymic' => [
                'nullable',
                'string',
            ],
            'password_confirmation' => [
                'required',
                Password::min(8),
                'same:password',
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'username' => 'Логин',
            'password' => 'Пароль',
            'email' => 'Электронная почта',
            'first_name' => 'Имя',
            'last_name' => 'Фамилия',
            'patronymic' => 'Отчество',
        ];
    }
}
