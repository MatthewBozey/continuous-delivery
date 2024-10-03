<?php

namespace App\Filters;

class DictionaryCheckResultFilter extends QueryFilter
{
    public function update_script(int $updateScript): mixed
    {
        return $this->builder->where('update_script', $updateScript);
    }
}
