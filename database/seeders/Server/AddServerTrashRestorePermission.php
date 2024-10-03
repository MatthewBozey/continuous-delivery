<?php

namespace Database\Seeders\Server;

use App\Models\PermissionSection;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class AddServerTrashRestorePermission extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $section = PermissionSection::where('sysname', 'server')->first();
        //
        $permission = Permission::updateOrCreate([
            'name' => 'server trash-restore',
            'title' => 'Восстановление удаленных серверов'], [
                'guard_name' => 'web',
                'permission_section_id' => $section->permission_section_id,
            ]);
        $permission->save();
    }
}
