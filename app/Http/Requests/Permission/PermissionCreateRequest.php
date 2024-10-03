<?php

namespace App\Http\Requests\Permission;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class PermissionCreateRequest extends FormRequest
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
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string'],
            'title' => ['required', 'string'],
            'permission_section_id' => ['required', 'integer', "exists:App\Models\PermissionSection,permission_section_id"],
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Правило',
            'title' => 'Название',
            'permission_section_id' => 'Секция',
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
