<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Permission list
        Permission::query()->delete();

        Permission::create(['name' => 'portal admin']);
        Permission::create(['name' => 'portal customer']);

        $superadmin = Role::create(['name' => 'Administrador']);
        $superadmin->givePermissionTo(Permission::all());

        $dev = Role::create(['name' => 'Desarrollador']);
        $dev->givePermissionTo(Permission::all());

    }
}
