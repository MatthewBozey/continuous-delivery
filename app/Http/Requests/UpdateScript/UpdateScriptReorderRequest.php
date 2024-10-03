<?php

namespace App\Http\Requests\UpdateScript;

use App\Http\Requests\FailedValidationHandleRequest;

class UpdateScriptReorderRequest extends FailedValidationHandleRequest
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

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'scripts' => ['required', 'array'],
        ];
    }

    public function attributes(): array
    {
        return ['scripts' => 'Конфигурационные скрипты'];
    }
}
