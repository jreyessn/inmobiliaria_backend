<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsSeederV2 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
         * channel customer
         */
        Permission::create(['name' => 'show messages customer tickets']);
        Permission::create(['name' => 'reply customer tickets']);

        Permission::create(['name' => 'details tickets']);
        Permission::create(['name' => 'details contact tickets']);
        Permission::create(['name' => 'details tracked tickets']);
        Permission::create(['name' => 'update tracked tickets']);
        Permission::create(['name' => 'update contact tickets']);
        Permission::create(['name' => 'update type tickets']);
        Permission::create(['name' => 'update status tickets']);
        Permission::create(['name' => 'update priority tickets']);
        Permission::create(['name' => 'update system tickets']);
        Permission::create(['name' => 'update attended tickets']);
        Permission::create(['name' => 'update assigned tickets']);
        Permission::create(['name' => 'show status reply customer tickets']);
        Permission::create(['name' => 'show status reply admin tickets']);
        Permission::create(['name' => 'close tickets']);

        $role = Role::where(['name' => 'Administrador'])->first();
        $role->givePermissionTo([
            'show messages customer tickets',
            'reply customer tickets',
            'details tickets',
            'details contact tickets',
            'details tracked tickets',
            'update tracked tickets',
            'update contact tickets',
            'update type tickets',
            'update status tickets',
            'update priority tickets',
            'update system tickets',
            'update attended tickets',
            'update assigned tickets',
            'show status reply customer tickets',
            'show status reply admin tickets',
            'close tickets',
        ]);

        $role = Role::where(['name' => 'Desarrollador'])->first();
        $role->givePermissionTo([
            'show messages customer tickets',
            'details tickets',
            'details contact tickets',
            'details tracked tickets',
            'update tracked tickets',
            'update status tickets',
            'show status reply admin tickets',
        ]);

    }
}
