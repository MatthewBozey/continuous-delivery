<?php

namespace Database\Seeders;

use App\Models\PermissionSection;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class AddDeleteUserAvatarPermissionToAdministrator extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $section = PermissionSection::create(['title' => 'Пользователи', 'sysname' => 'user'])->first();
        $permission = Permission::create([
            'name' => 'user delete_avatar_for_administrator',
            'title' => 'Удаление аватаров других пользователей',
            'guard_name' => 'web',
            'permission_section_id' => $section->permission_section_id,
        ]);
        $permission->save();
    }
}
