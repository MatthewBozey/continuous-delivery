<?php

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class UnauthorizedException extends HttpException
{
    private $requiredRoles = [];

    private $requiredPermissions = [];

    public static function forRoles(array $roles): self
    {
        $message = 'У пользователя нет нужных ролей.';

        if (config('permission.display_role_in_exception')) {
            $message .= ' Необходимыми ролями являются '.implode(', ', $roles);
        }

        $exception = new static(403, $message, null, []);
        $exception->requiredRoles = $roles;

        return $exception;
    }

    public static function forPermissions(array $permissions): self
    {
        $message = 'У пользователя нет нужных разрешений.';

        if (config('permission.display_permission_in_exception')) {
            $message .= ' Необходимыми разрешениями являются '.implode(', ', $permissions);
        }

        $exception = new static(403, $message, null, []);
        $exception->requiredPermissions = $permissions;

        return $exception;
    }

    public static function forRolesOrPermissions(array $rolesOrPermissions): self
    {
        $message = 'Пользователь не имеет никаких необходимых прав доступа.';

        if (config('permission.display_permission_in_exception') && config('permission.display_role_in_exception')) {
            $message .= ' Необходимыми ролями или разрешениями являются '.implode(', ', $rolesOrPermissions);
        }

        $exception = new static(403, $message, null, []);
        $exception->requiredPermissions = $rolesOrPermissions;

        return $exception;
    }

    public static function notLoggedIn(): self
    {
        return new static(403, 'Пользователь не вошел в систему.', null, []);
    }

    public function getRequiredRoles(): array
    {
        return $this->requiredRoles;
    }

    public function getRequiredPermissions(): array
    {
        return $this->requiredPermissions;
    }
}
