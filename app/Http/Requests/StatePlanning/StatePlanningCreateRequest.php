<?php

namespace App\Http\Requests\StatePlanning;

use App\Http\Requests\FailedValidationHandleRequest;

class StatePlanningCreateRequest extends FailedValidationHandleRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status_title' => ['required', 'string', 'unique:\App\Models\StatePlanning,status_title'],
            'status_name' => ['required', 'string', 'unique:\App\Models\StatePlanning,status_name'],
            'status_color' => ['nullable', 'regex:/([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
        ];
    }

    public function attributes(): array
    {
        return [
            'status_title' => 'Название состояния',
            'status_name' => 'Системное название состояния',
            'status_color' => 'Цвет состояния',
        ];
    }
}
