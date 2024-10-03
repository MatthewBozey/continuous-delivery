<?php

namespace App\Http\Requests\PermissionSection;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class PermissionSectionCreateRequest extends FormRequest
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
            'title' => "required|string|unique:App\Models\PermissionSection,title",
            'sysname' => "required|string|unique:App\Models\PermissionSection,sysname",
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => 'Название',
            'sysname' => 'Системное название',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        $errors = $validator->errors()->all();

        $response = response()->json([
            'message' => 'Ошибка валидации параметров',
            'error_list' => $errors,
        ], 422);

        throw new HttpResponseException($response);
    }
}
