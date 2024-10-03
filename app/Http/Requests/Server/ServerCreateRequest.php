<?php

namespace App\Http\Requests\Server;

use App\Http\Requests\FailedValidationHandleRequest;
use Illuminate\Contracts\Validation\ValidationRule;

class ServerCreateRequest extends FailedValidationHandleRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'server_name' => ['required', 'string', 'unique:App\Models\Server,server_name'],
            'database_name' => ['required', 'string'],
            'database_user' => ['required', 'string'],
            'database_password' => ['required', 'string'],
            'ip_address' => ['required', 'ip'],
            'port' => [
                'nullable',
                'integer',
                'min:0',
                'max:65535',
            ],
            'disabled' => ['nullable', 'bool'],
            'update_required' => ['nullable', 'bool'],
        ];
    }

    public function attributes(): array
    {
        return [
            'server_name' => 'Название сервера',
            'database_name' => 'Название базы данных',
            'database_user' => 'Пользователь базы данных',
            'database_password' => 'Пароль',
            'ip_address' => 'Ip адрес',
            'port' => 'Порт',
            'disabled' => 'Отключено',
            'update_required' => 'Обязательны обновления',
        ];
    }
}
