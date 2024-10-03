<?php

namespace App\Filters;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class UpdatePackageFilter extends QueryFilter
{
    public function package_create_date($date): \Illuminate\Database\Eloquent\Builder
    {
        return $this->builder->whereDate('package_create_date', Carbon::parse($date)->toDateString());
    }

    public function package_done_date($date): \Illuminate\Database\Eloquent\Builder
    {
        return $this->builder->whereDate('package_done_date', $date);
    }

    public function package_type_id(int $typeId): \Illuminate\Database\Eloquent\Builder
    {
        return $this->builder->where('package_type_id', $typeId);
    }

    public function project_id(mixed $projectId): \Illuminate\Database\Eloquent\Builder
    {
        return $this->builder->when(is_array($projectId), function (Builder $query) use ($projectId) {
            $query->whereIn('project_id', $projectId);
        }, function (Builder $query) use ($projectId) {
            $query->where('project_id', $projectId);
        });
    }

    public function verified(bool $value): \Illuminate\Database\Eloquent\Builder
    {
        return $this->builder->where('verified', $value);
    }

    public function has_errors(bool $value): \Illuminate\Database\Eloquent\Builder
    {
        return $this->builder->where('has_errors', $value);
    }

    public function version(int $value): \Illuminate\Database\Eloquent\Builder
    {
        return $this->builder->where('version', $value);
    }

    public function author_name(mixed $author): \Illuminate\Database\Eloquent\Builder
    {
        return $this->builder->when(is_array($author), function (Builder $query) use ($author) {
            $query->where(function ($q) use ($author) {
                foreach ($author as $name) {
                    $q->orWhere('author_name', 'LIKE', '%'.$name.'%');
                }
            });
        }, function (Builder $query) use ($author) {
            $query->where('author_name', 'LIKE', '%'.$author.'%');
        });
    }

    public function update_package_id(int $id): \Illuminate\Database\Eloquent\Builder
    {
        return $this->builder->where('update_package_id', $id);
    }
}
