<?php

namespace Database\Seeders;

use App\Models\PermissionSection;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class AddPermissionToViewUpdatePackages extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissionSection = PermissionSection::whereSysname('update_packages')->first();

        Permission::create([
            'name' => 'update-package view-all',
            'title' => 'Просмотр всех конфигурационных пакетов',
            'guard_name' => 'web',
            'permission_section_id' => $permissionSection->permission_section_id,
        ]);
    }
}
