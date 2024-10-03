<?php

namespace App\Http\Requests\Server;

use App\Http\Requests\FailedValidationHandleRequest;

class ServerUpdateRequest extends FailedValidationHandleRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'server_name' => [
                'nullable', 'string', 'unique:App\Models\Server,server_name,'.$this->server_id.',server_id',
            ],
            'database_name' => ['nullable', 'string'],
            'database_user' => ['nullable', 'string'],
            'database_password' => ['nullable', 'string'],
            'ip_address' => ['nullable', 'ip'],
            'port' => ['nullable', 'integer'],
            'disabled' => ['nullable', 'bool'],
            'update_required' => ['nullable', 'bool'],
        ];
    }

    public function attributes()
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
