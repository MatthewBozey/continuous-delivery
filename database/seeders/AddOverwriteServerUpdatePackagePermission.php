<?php

namespace Database\Seeders;

use App\Models\PermissionSection;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class AddOverwriteServerUpdatePackagePermission extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $section = PermissionSection::where('sysname', 'update_packages')->first();
        $permission = Permission::create([
            'name' => 'update-package overwrite-server',
            'title' => 'Перезапись уже обновленных серверов',
            'guard_name' => 'web',
            'permission_section_id' => $section->permission_section_id,
        ]);
        $permission->save();
    }
}
