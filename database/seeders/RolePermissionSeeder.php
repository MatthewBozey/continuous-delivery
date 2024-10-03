<?php

namespace Database\Seeders;

use App\Models\PermissionSection;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissionSection = PermissionSection::create(['title' => 'Роли', 'sysname' => 'role']);
        $permissionSection->save();

        Permission::create(['name' => 'role list', 'title' => 'Получение списка ролей', 'guard_name' => 'web', 'permission_section_id' => $permissionSection->permission_section_id]);
        Permission::create(['name' => 'role show', 'title' => 'Получение роли', 'guard_name' => 'web', 'permission_section_id' => $permissionSection->permission_section_id]);
        Permission::create(['name' => 'role create', 'title' => 'Создание роли', 'guard_name' => 'web', 'permission_section_id' => $permissionSection->permission_section_id]);
        Permission::create(['name' => 'role edit', 'title' => 'Редактирование роли', 'guard_name' => 'web', 'permission_section_id' => $permissionSection->permission_section_id]);
        Permission::create(['name' => 'role delete', 'title' => 'Удаление роли', 'guard_name' => 'web', 'permission_section_id' => $permissionSection->permission_section_id]);
    }
}
