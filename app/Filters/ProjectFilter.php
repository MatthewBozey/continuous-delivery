<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

class ProjectFilter extends QueryFilter
{
    public function project_name(string $projectName): Builder
    {
        return $this->builder->where('project_name', 'like', '%'.$projectName.'%');
    }

    public function project_sysname(string $projectSysname): Builder
    {
        return $this->builder->where('project_sysname', 'like', '%'.$projectSysname.'%');
    }

    public function project_title(string $projectTitle): Builder
    {
        return $this->builder->where('project_title', 'like', '%'.$projectTitle.'%');
    }

    public function project_desc(string $projectDesc): Builder
    {
        return $this->builder->where('project_desc', 'like', '%'.$projectDesc.'%');
    }

    public function to_cd(string $toCd): Builder
    {
        return $this->builder->where('to_cd', $toCd);
    }
}
