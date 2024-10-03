<?php

namespace Database\Seeders;

use App\Models\PermissionSection;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class UpdatePackageColorPermissions extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissionSection = PermissionSection::create(['title' => 'Персонализация конфигурационных пакето', 'sysname' => 'update_package_color']);
        $permissionSection->save();

        Permission::create(['name' => 'update_package_color list', 'title' => 'Получение списка ', 'guard_name' => 'web', 'permission_section_id' => $permissionSection->permission_section_id]);
        Permission::create(['name' => 'update_package_color show', 'title' => 'Получение ', 'guard_name' => 'web', 'permission_section_id' => $permissionSection->permission_section_id]);
        Permission::create(['name' => 'update_package_color create', 'title' => 'Создание ', 'guard_name' => 'web', 'permission_section_id' => $permissionSection->permission_section_id]);
        Permission::create(['name' => 'update_package_color edit', 'title' => 'Редактирование ', 'guard_name' => 'web', 'permission_section_id' => $permissionSection->permission_section_id]);
        Permission::create(['name' => 'update_package_color delete', 'title' => 'Удаление ', 'guard_name' => 'web', 'permission_section_id' => $permissionSection->permission_section_id]);
    }
}
