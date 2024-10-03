<?php

namespace Database\Seeders;

use App\Models\PermissionSection;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class StatePlanningPermissionSeeder extends Seeder
{
    /** @return void */
    public function run()
    {
        $permissionSection = PermissionSection::create(['title' => 'Запланированные состояния', 'sysname' => 'state_planning']);
        $permissionSection->save();

        Permission::create(['name' => 'state-planning list', 'title' => 'Получение списка запланированных состояний', 'guard_name' => 'web', 'permission_section_id' => $permissionSection->permission_section_id]);
        Permission::create(['name' => 'state-planning show', 'title' => 'Получение запланированного состояния', 'guard_name' => 'web', 'permission_section_id' => $permissionSection->permission_section_id]);
        Permission::create(['name' => 'state-planning create', 'title' => 'Создание запланированного состояния', 'guard_name' => 'web', 'permission_section_id' => $permissionSection->permission_section_id]);
        Permission::create(['name' => 'state-planning edit', 'title' => 'Редактирование запланированного состояния', 'guard_name' => 'web', 'permission_section_id' => $permissionSection->permission_section_id]);
        Permission::create(['name' => 'state-planning delete', 'title' => 'Удаление запланированного состояния', 'guard_name' => 'web', 'permission_section_id' => $permissionSection->permission_section_id]);
    }
}
