<?php

namespace Database\Seeders\Server;

use Illuminate\Database\Seeder;

class SoftDeletesPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            AddServerTrashForceDeletePermission::class,
            AddServerTrashRestorePermission::class,
            ServerTrashPermissionSeeder::class,
        ]);
    }
}
