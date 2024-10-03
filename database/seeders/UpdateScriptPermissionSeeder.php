<?php

namespace Database\Seeders;

use App\Models\PermissionSection;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class UpdateScriptPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissionSection = PermissionSection::create(['title' => 'Конфигурационные скрипты', 'sysname' => 'update_scripts']);
        $permissionSection->save();

        Permission::create(['name' => 'update-script list', 'title' => 'Получение списка конфигурационных скриптов', 'guard_name' => 'web', 'permission_section_id' => $permissionSection->permission_section_id]);
        Permission::create(['name' => 'update-script show', 'title' => 'Получение конфигурационного скрипта', 'guard_name' => 'web', 'permission_section_id' => $permissionSection->permission_section_id]);
        Permission::create(['name' => 'update-script create', 'title' => 'Создание конфигурационного скрипта', 'guard_name' => 'web', 'permission_section_id' => $permissionSection->permission_section_id]);
        Permission::create(['name' => 'update-script edit', 'title' => 'Редактирование конфигурационного скрипта', 'guard_name' => 'web', 'permission_section_id' => $permissionSection->permission_section_id]);
        Permission::create(['name' => 'update-script delete', 'title' => 'Удаление конфигурационного скрипта', 'guard_name' => 'web', 'permission_section_id' => $permissionSection->permission_section_id]);
        Permission::create(['name' => 'update-script reorder', 'title' => 'Изменение порядка конфигурационных скрипов', 'guard_name' => 'web', 'permission_section_id' => $permissionSection->permission_section_id]);
        /*$permissionSection = PermissionSection::where('sysname', 'update_scripts')->first();*/
    }
}
