<?php

namespace App\Http\Requests\Server;

use App\Http\Requests\FailedValidationHandleRequest;

class CreateServerRequest extends FailedValidationHandleRequest
{
    /** @return bool */
    public function authorize()
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'server_name' => ['required', 'string', 'unique:\App\Models\Server,server_id'],
        ];
    }
}
