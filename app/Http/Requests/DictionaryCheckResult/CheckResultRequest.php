<?php

namespace App\Http\Requests\DictionaryCheckResult;

use App\Http\Requests\FailedValidationHandleRequest;
use Illuminate\Contracts\Validation\ValidationRule;

class CheckResultRequest extends FailedValidationHandleRequest
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
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'update_script' => ['required', 'integer'],
        ];
    }

    /**
     * @return string[]
     */
    public function attributes(): array
    {
        return [
            'update_script' => 'Уникальный идентификатор скрипта',
        ];
    }
}
