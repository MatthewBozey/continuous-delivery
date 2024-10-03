<?php

namespace App\Http\Requests\Project;

use App\Http\Requests\FailedValidationHandleRequest;

class ProjectUpdateRequest extends FailedValidationHandleRequest
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
            'project_name' => [
                'nullable',
                'string',
                'unique:App\Models\Project,project_name,'.$this->project_id.',project_id',
            ],
            'project_sysname' => [
                'nullable',
                'string',
                'unique:App\Models\Project,project_sysname,'.$this->project_id.',project_id',
            ],
            'project_title' => [
                'nullable',
                'string',
                'unique:App\Models\Project,project_title,'.$this->project_id.',project_id',
            ],
            'project_desc' => [
                'nullable',
                'string',
            ],
            'to_cd' => [
                'nullable',
                'bool',
            ],
            'server_ids' => [
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
        ];
    }
}
