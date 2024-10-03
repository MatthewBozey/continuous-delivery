<?php

namespace App\Filters;

class UpdateErrorLogFilter extends QueryFilter
{
    /**
     * @return mixed
     */
    public function update_script(int $updateScript)
    {
        return $this->builder->where('update_script_id', $updateScript);
    }
}
