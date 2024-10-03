<?php

namespace App\Http\Requests\UpdatePackageColor;

use App\Http\Requests\FailedValidationHandleRequest;
use DB;

class UpdatePackageColorCreateRequest extends FailedValidationHandleRequest
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
            'min_value' => ['required', 'integer', 'min:0', 'max:100', $this->uniqueRangeRule()],
            'max_value' => ['required', 'integer', 'min:0', 'max:100', 'gte:min_value', $this->uniqueRangeRule()],
            'color' => ['nullable', 'string'],
        ];
    }

    protected function uniqueRangeRule(): \Closure
    {
        return static function ($attribute, $value, $fail) {
            $field = str_replace('_value', '', $attribute);

            // Проверяем уникальность значения для поля
            $exists = DB::table('update.update_package_color')
                ->where(function ($query) use ($value) {
                    $query->where('min_value', '<=', $value)
                        ->where('max_value', '>=', $value);
                })
                ->orWhere(function ($query) use ($value) {
                    $query->where('min_value', '>=', $value)
                        ->where('min_value', '<=', $value);
                })
                ->exists();

            if ($exists) {
                $fail("Значение $field должно быть уникальным.");
            }
        };
    }
}
