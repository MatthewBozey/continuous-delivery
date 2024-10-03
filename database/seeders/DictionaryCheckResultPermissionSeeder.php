<?php

namespace Database\Seeders;

use App\Models\PermissionSection;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class DictionaryCheckResultPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissionSection = PermissionSection::updateOrCreate([
            'title' => 'Результат проверки справочников',
            'sysname' => 'dictionary_check_result',
        ]);
        $permissionSection->save();

        Permission::updateOrCreate([
            'name' => 'dictionary_check_result list',
            'title' => 'Получение списка Результат проверки справочников',
            'guard_name' => 'web',
            'permission_section_id' => $permissionSection->permission_section_id,
        ]);
        Permission::updateOrCreate([
            'name' => 'dictionary_check_result show',
            'title' => 'Получение Результат проверки справочников',
            'guard_name' => 'web',
            'permission_section_id' => $permissionSection->permission_section_id,
        ]);
        Permission::updateOrCreate([
            'name' => 'dictionary_check_result create',
            'title' => 'Создание Результат проверки справочников',
            'guard_name' => 'web',
            'permission_section_id' => $permissionSection->permission_section_id,
        ]);
        Permission::updateOrCreate([
            'name' => 'dictionary_check_result edit',
            'title' => 'Редактирование Результат проверки справочников',
            'guard_name' => 'web',
            'permission_section_id' => $permissionSection->permission_section_id,
        ]);
        Permission::updateOrCreate([
            'name' => 'dictionary_check_result delete',
            'title' => 'Удаление Результат проверки справочников',
            'guard_name' => 'web',
            'permission_section_id' => $permissionSection->permission_section_id,
        ]);
    }
}
