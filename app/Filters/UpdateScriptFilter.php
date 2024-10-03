<?php

namespace App\Filters;

class UpdateScriptFilter extends QueryFilter
{
    /**
     * @return mixed
     */
    public function update_package(int $updatePackage)
    {
        return $this->builder->where('update_package_id', $updatePackage);
    }
}
