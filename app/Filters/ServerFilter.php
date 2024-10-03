<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

class ServerFilter extends QueryFilter
{
    public function server_name(string $serverName): Builder
    {
        return $this->builder->where('server_name', 'like', '%'.$serverName.'%');
    }

    public function database_name(string $databaseName): Builder
    {
        return $this->builder->when(auth()->user()->can('server full-list'), static fn ($query) => $query->where('database_name', 'like', '%'.$databaseName.'%'));
    }

    public function database_user(string $databaseUser): Builder
    {
        return $this->builder->when(auth()->user()->can('server full-list'), static fn ($query) => $query->where('database_user', 'like', '%'.$databaseUser.'%'));
    }

    public function database_password(string $databasePassword): Builder
    {
        return $this->builder->when(auth()->user()->can('server full-list'), static fn ($query) => $query->where('database_password', $databasePassword));
    }

    public function ip_address(string $ipAddress): Builder
    {
        return $this->builder->when(auth()->user()->can('server full-list'), static fn ($query) => $query->where('ip_address', $ipAddress));
    }
}
