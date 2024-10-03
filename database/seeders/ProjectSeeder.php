<?php

namespace Database\Seeders;

use App\Models\PermissionSection;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $permissionSection = PermissionSection::updateOrCreate(['title' => 'Проекты',
            'sysname' => 'project',
        ]);
        $permissionSection->save();

        Permission::updateOrCreate(['name' => 'project list',
            'title' => 'Получение списка проектов',
            'guard_name' => 'web',
            'permission_section_id' => $permissionSection->permission_section_id,
        ]);
        Permission::updateOrCreate(['name' => 'project show',
            'title' => 'Получение проекта',
            'guard_name' => 'web',
            'permission_section_id' => $permissionSection->permission_section_id,
        ]);
        Permission::updateOrCreate(['name' => 'project create',
            'title' => 'Создание проекта',
            'guard_name' => 'web',
            'permission_section_id' => $permissionSection->permission_section_id,
        ]);
        Permission::updateOrCreate(['name' => 'project edit',
            'title' => 'Редактирование проекта',
            'guard_name' => 'web',
            'permission_section_id' => $permissionSection->permission_section_id,
        ]);
        Permission::updateOrCreate(['name' => 'project delete',
            'title' => 'Удаление проекта',
            'guard_name' => 'web',
            'permission_section_id' => $permissionSection->permission_section_id,
        ]);
    }
}
