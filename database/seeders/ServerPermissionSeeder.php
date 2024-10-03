<?php

namespace Database\Seeders;

use App\Models\PermissionSection;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class ServerPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissionSection = PermissionSection::create(['title' => 'Сервера', 'sysname' => 'server']);
        $permissionSection->save();

        Permission::create(['name' => 'server list', 'title' => 'Получение серверов', 'guard_name' => 'web', 'permission_section_id' => $permissionSection->permission_section_id]);
        Permission::create(['name' => 'server show', 'title' => 'Получение сервера', 'guard_name' => 'web', 'permission_section_id' => $permissionSection->permission_section_id]);
        Permission::create(['name' => 'server create', 'title' => 'Создание сервера', 'guard_name' => 'web', 'permission_section_id' => $permissionSection->permission_section_id]);
        Permission::create(['name' => 'server edit', 'title' => 'Редактирование сервера', 'guard_name' => 'web', 'permission_section_id' => $permissionSection->permission_section_id]);
        Permission::create(['name' => 'server delete', 'title' => 'Удаление сервера', 'guard_name' => 'web', 'permission_section_id' => $permissionSection->permission_section_id]);
    }
}
