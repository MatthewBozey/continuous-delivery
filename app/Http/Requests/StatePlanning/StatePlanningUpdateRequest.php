<?php

namespace App\Http\Requests\StatePlanning;

use App\Http\Requests\FailedValidationHandleRequest;

class StatePlanningUpdateRequest extends FailedValidationHandleRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        \Log::info($this->id);

        return [
            'status_title' => [
                'nullable', 'string', 'unique:App\Models\StatePlanning,status_title,'.$this->id.',server_status_id',
            ],
            'status_name' => [
                'nullable', 'string', 'unique:App\Models\StatePlanning,status_name,'.$this->id.',server_status_id',
            ],
            'status_color' => ['nullable', 'regex:/([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
        ];
    }

    public function attributes(): array
    {
        return ['state_title' => 'Название состояния', 'state_code' => 'Системное название состояния', 'state_color' => 'Цвет состояния'];
    }
}
