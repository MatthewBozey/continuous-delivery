<?php

namespace Database\Seeders;

use App\Models\PermissionSection;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class AddServerPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $section = PermissionSection::whereSysname('server')->value('permission_section_id');
        if ($section) {
            Permission::updateOrCreate(['name' => 'server full-list', 'permission_section_id' => $section],
                ['title' => 'Просмотр полного списка параметров серверов']);
        }
    }
}
