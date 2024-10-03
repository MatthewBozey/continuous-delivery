<?php

namespace App\Http\Requests\UpdatePackage;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UpdatePackageCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'project_id' => ['required', 'integer', "exists:App\Models\Project,project_id"],
            'package_type_id' => ['required', 'integer', "exists:App\Models\PackageType,package_type_id"],
            'version' => ['nullable', Rule::requiredIf($this->package_type_id === 4), 'integer'],
            'package_create_date' => ['required', 'date'],
            'package_done_date' => ['nullable', 'date'],
            'package_plan_date' => ['nullable', 'date'],
            'verified' => ['nullable', 'boolean'],
            'has_errors' => ['nullable', 'boolean'],
        ];
    }

    /** @return string[] */
    public function attributes(): array
    {
        return [
            'project_id' => 'Проект',
            'package_type_id' => 'Тип пакета',
            'version' => 'Версия',
            'package_create_date' => 'Дата создания конфигурационного пакета',
            'package_done_date' => 'Дата выполнения конфигурационного пакета',
            'package_plan_date' => 'Дата планирования конфигурационного пакета',
            'verified' => 'Конфигурационный пакет обработан',
            'has_errors' => 'Конфигурационный пакет имеет ошибки',
        ];
    }

    /**
     * @return HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'message' => 'Произошла ошибка валидации',
                'error_list' => $validator->errors()->all(),
            ], 422)
        );
    }
}
