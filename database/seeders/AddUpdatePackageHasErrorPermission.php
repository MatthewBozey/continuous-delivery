<?php

namespace Database\Seeders;

use App\Models\PermissionSection;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class AddUpdatePackageHasErrorPermission extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $section = PermissionSection::where('sysname', 'update_packages')->first();
        //
        $permission = Permission::updateOrCreate([
            'name' => 'update-package update-package-has-error',
            'title' => 'Редактирование значения Пакет имеет ошибки'], [
                'guard_name' => 'web',
                'permission_section_id' => $section->permission_section_id,
            ]);
        $permission->save();
    }
}
