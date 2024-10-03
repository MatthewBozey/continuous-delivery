<?php

namespace App\Http\Requests\UpdateScript;

use App\Http\Requests\FailedValidationHandleRequest;

class UpdateScriptUpdateRequest extends FailedValidationHandleRequest
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
            'update_package_id' => ['required', 'int', 'exists:\App\Models\UpdatePackage,update_package_id'],
            'is_done' => ['nullable', 'boolean'],
            'script_name' => ['required', 'string'],
            'script_text' => ['required', 'string'],
            'script_type_id' => ['required', 'integer', 'exists:\App\Models\ScriptType,script_type_id'],
        ];
    }
}
