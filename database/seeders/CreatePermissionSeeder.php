<?php

namespace Database\Seeders;

use App\Models\PermissionSection;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CreatePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*Role::create(["name" => "super-admin", "title" => "Главный администратор", "guard_name" => "web"]);
        Role::create(["name" => "admin", "title" => "Администратор", "guard_name" => "web"]);
        Role::create(["name" => "devops", "title" => "Devops", "guard_name" => "web"]);
        Role::create(["name" => "develop", "title" => "Разработчик", "guard_name" => "web"]);*/

        Permission::create(['name' => 'users list', 'title' => 'Получение списка пользователей', 'guard_name' => 'web', 'permission_section_id' => 1]);
        Permission::create(['name' => 'users create', 'title' => 'Создание пользователя', 'guard_name' => 'web', 'permission_section_id' => 1]);
        Permission::create(['name' => 'users show', 'title' => 'Получение пользователя', 'guard_name' => 'web', 'permission_section_id' => 1]);
        Permission::create(['name' => 'users edit', 'title' => 'Редактирование данных пользователя', 'guard_name' => 'web', 'permission_section_id' => 1]);
        Permission::create(['name' => 'users delete', 'title' => 'Удаление пользователя', 'guard_name' => 'web', 'permission_section_id' => 1]);
        Permission::create(['name' => 'roles list', 'title' => 'Получение списка ролей', 'guard_name' => 'web', 'permission_section_id' => 2]);
        Permission::create(['name' => 'roles create', 'title' => 'Создание роли', 'guard_name' => 'web', 'permission_section_id' => 2]);
        Permission::create(['name' => 'roles show', 'title' => 'Получение роли', 'guard_name' => 'web', 'permission_section_id' => 2]);
        Permission::create(['name' => 'roles edit', 'title' => 'Редактирование данных роли', 'guard_name' => 'web', 'permission_section_id' => 2]);
        Permission::create(['name' => 'roles delete', 'title' => 'Удаление роли', 'guard_name' => 'web', 'permission_section_id' => 2]);

        $superAdmin = Role::findByName('admin');
        $superAdmin->givePermissionTo([
            'users list',
            'users create',
            'users show',
            'users edit',
            'users delete',
            'roles list',
            'roles create',
            'roles show',
            'roles edit',
            'roles delete',
        ]);

        $permissionSection = PermissionSection::create(['title' => 'Конфигурационные пакеты', 'sysname' => 'update_packages']);
        $permissionSection->save();

        Permission::create(['name' => 'update-package list', 'title' => 'Получение списка конфигурационных пакетов', 'guard_name' => 'web', 'permission_section_id' => $permissionSection->permission_section_id]);
        Permission::create(['name' => 'update-package show', 'title' => 'Получение конфигурационного пакета', 'guard_name' => 'web', 'permission_section_id' => $permissionSection->permission_section_id]);
        Permission::create(['name' => 'update-package create', 'title' => 'Создание конфигурационного пакета', 'guard_name' => 'web', 'permission_section_id' => $permissionSection->permission_section_id]);
        Permission::create(['name' => 'update-package edit', 'title' => 'Редактирование конфигурационного пакета', 'guard_name' => 'web', 'permission_section_id' => $permissionSection->permission_section_id]);
        Permission::create(['name' => 'update-package delete', 'title' => 'Удаление конфигурационного пакета', 'guard_name' => 'web', 'permission_section_id' => $permissionSection->permission_section_id]);

    }
}
