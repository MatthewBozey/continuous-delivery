<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserDeleteAvatarRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'user_id' => ['required', 'integer', 'exists:App\Models\User,user_id'],
        ];
    }
}
