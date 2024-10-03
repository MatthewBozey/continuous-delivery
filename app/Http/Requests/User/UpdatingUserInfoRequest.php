<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdatingUserInfoRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'username' => ['required', 'string', 'unique:App\Models\User,username,'.$this->user_id],
            'last_name' => ['required', 'string'],
            'first_name' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:App\Models\User,email,'.$this->user_id],
            'patronymic' => ['required', 'string'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg'],
            'user_id' => ['required', 'integer', 'exists:App\Models\User,user_id'],
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
            'avatar' => 'Фотография пользователя',
        ];
    }
}
