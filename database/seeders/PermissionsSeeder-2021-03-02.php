<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionsV1Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Permission::create(['name' => 'dashboard']);

        Permission::create(['name' => 'show tickets']);
        Permission::create(['name' => 'store tickets']);
        Permission::create(['name' => 'destroy tickets']);
        Permission::create(['name' => 'update tickets']);
        Permission::create(['name' => 'reply tickets']);

        Permission::create(['name' => 'show customers']);
        Permission::create(['name' => 'store customers']);
        Permission::create(['name' => 'destroy customers']);
        Permission::create(['name' => 'update customers']);
        
        Permission::create(['name' => 'show contacts']);
        Permission::create(['name' => 'store contacts']);
        Permission::create(['name' => 'destroy contacts']);
        Permission::create(['name' => 'update contacts']);

        Permission::create(['name' => 'show groups']);
        Permission::create(['name' => 'store groups']);
        Permission::create(['name' => 'destroy groups']);
        Permission::create(['name' => 'update groups']);

        Permission::create(['name' => 'show systems']);
        Permission::create(['name' => 'store systems']);
        Permission::create(['name' => 'destroy systems']);
        Permission::create(['name' => 'update systems']);

        Permission::create(['name' => 'show users']);
        Permission::create(['name' => 'store users']);
        Permission::create(['name' => 'destroy users']);
        Permission::create(['name' => 'update users']);

        Permission::create(['name' => 'show reports systems']);

        $role = Role::where(['name' => 'Administrador'])->first();
        $role->givePermissionTo(Permission::all());

        $role = Role::where(['name' => 'Desarrollador'])->first();
        $role->givePermissionTo(Permission::all());

        $role = Role::where(['name' => 'Cliente'])->first();
        $role->givePermissionTo([
            "show tickets",
            "store tickets",
            "reply tickets",
        ]);

    }
}
