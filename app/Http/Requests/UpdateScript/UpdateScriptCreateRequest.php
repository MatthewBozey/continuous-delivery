<?php

namespace App\Http\Requests\UpdateScript;

use App\Http\Requests\FailedValidationHandleRequest;

class UpdateScriptCreateRequest extends FailedValidationHandleRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'update_script_id' => ['nullable', 'integer'],
            'update_package_id' => ['required', 'integer', 'exists:\App\Models\UpdatePackage,update_package_id'],
            'script_date' => ['required', 'date'],
            'script_text' => ['required', 'string', 'unique:\App\Models\UpdateScript,script_text'],
            'is_done' => ['required', 'bool'],
            'script_done_date' => ['nullable', 'date', 'after_or_equal:script_date'],
            'script_name' => ['required', 'string'],
            'script_order' => ['nullable', 'integer'],
            'script_type_id' => ['required', 'integer'],
        ];
    }

    public function attributes()
    {
        return [
            'update_script_id' => 'Уникальный идентификатор скрипта',
            'update_package_id' => 'Уникальный идентификатор пакета',
            'script_date' => 'Дата создания скрипта',
            'script_text' => 'Текст скрипта',
            'is_done' => 'Скрипт выполнен в ref',
            'script_done_date' => 'Дата выполнения скрипта в ref',
            'script_name' => 'Название скрипта',
            'script_order' => 'Порядок скрипта',
            'script_type_id' => 'Тип скрипта',
        ];
    }
}
