<?php

namespace App\Http\Requests\Role;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RoleCreateRequest extends FormRequest
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
            'name' => ['required', 'string'],
            'title' => ['required', 'string'],
            'guard_name' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'name' => 'Системное название роли',
            'title' => 'Название роли',
            'guard_name' => 'Гуард',
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
