<?php

namespace App\Http\Requests\UpdatePackageColor;

use App\Http\Requests\FailedValidationHandleRequest;

class UpdatePackageColorUpdateRequest extends FailedValidationHandleRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'min_value' => ['required', 'integer', 'min:0', 'max:100'],
            'max_value' => ['required', 'integer', 'min:0', 'max:100', 'gte:min_value'],
            'color' => ['nullable', 'string'],
        ];
    }
}
