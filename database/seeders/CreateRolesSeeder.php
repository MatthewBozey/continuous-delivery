<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class CreateRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create(['name' => 'super-admin', 'title' => 'Главный администратор', 'guard_name' => 'web']);
        Role::create(['name' => 'admin', 'title' => 'Администратор', 'guard_name' => 'web']);
        Role::create(['name' => 'devops', 'title' => 'Devops', 'guard_name' => 'web']);
        Role::create(['name' => 'develop', 'title' => 'Разработчик', 'guard_name' => 'web']);
    }
}
