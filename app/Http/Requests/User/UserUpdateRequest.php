<?php

namespace App\Http\Requests\User;

use App\Http\Requests\FailedValidationHandleRequest;
use Illuminate\Validation\Rules\Password;

class UserUpdateRequest extends FailedValidationHandleRequest
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
     */
    public function rules(): array
    {
        return [
            'username' => ['required', 'string', "unique:App\Models\User,username,".$this->user_id],
            'password' => [
                'nullable',
                'confirmed',
                Password::min(8)->letters()->mixedCase()->numbers()->symbols()->uncompromised()],
            'email' => ['required', 'email', "unique:App\Models\User,email,".$this->user_id],
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'patronymic' => ['nullable', 'string'],
            'password_confirmation' => [
                'required_with::password',
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

    public function messages(): array
    {
        return [
            'custom' => [
                'symbols' => 'Пароль должен содержать хотя бы один специальный символ (например, !@#$%^&*).',
                'numbers' => 'Пароль должен содержать хотя бы одну цифру.',
            ],
        ];
    }
}
