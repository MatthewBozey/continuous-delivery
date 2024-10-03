<?php

namespace App\Http\Requests\Project;

use App\Http\Requests\FailedValidationHandleRequest;

class ProjectCreateRequest extends FailedValidationHandleRequest
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
            'project_name' => ['required', 'string', 'unique:App\Models\Project,project_name'],
            'project_sysname' => ['required', 'string', 'unique:App\Models\Project,project_sysname'],
            'project_title' => ['required', 'string', 'unique:App\Models\Project,project_title'],
            'project_desc' => ['nullable', 'string'],
            'to_cd' => ['nullable', 'bool'],
            'server_ids' => ['nullable', 'array'],
            'required_server' => [
                'nullable',
                'array',
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'project_id' => 'ID',
            'project_name' => 'Название проекта',
            'project_sysname' => 'Системное название',
            'project_title' => 'Заголовок',
            'project_desc' => 'Описание',
            'to_cd' => 'В CD',
            'required_server' => 'Обязательные сервера',
        ];
    }
}
