<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class SaveUserSettingRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'code' => ['required', 'string'],
            'value' => ['required'],
            'user_id' => ['required', 'integer', 'exists:App\Models\User,user_id'],
        ];
    }
}
