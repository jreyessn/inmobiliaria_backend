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
        Role::where(['name' => 'Administrador'])->first()->syncPermissions([]);
        Role::where(['name' => 'Desarrollador'])->first()->syncPermissions([]);
        Role::where(['name' => 'Cliente'])->first()->syncPermissions([]);
        Permission::query()->delete();

        Permission::create(['name' => 'portal admin']);
        Permission::create(['name' => 'portal customer']);
        Permission::create(['name' => 'dashboard']);

        Permission::create(['name' => 'list tickets']);
        Permission::create(['name' => 'show tickets']);
        Permission::create(['name' => 'store tickets']);
        Permission::create(['name' => 'destroy tickets']);
        Permission::create(['name' => 'update tickets']);
        Permission::create(['name' => 'reply tickets']);
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
        Permission::create(['name' => 'close tickets']);
        Permission::create(['name' => 'only assigned tickets']);

        Permission::create(['name' => 'list customers']);
        Permission::create(['name' => 'show customers']);
        Permission::create(['name' => 'store customers']);
        Permission::create(['name' => 'destroy customers']);
        Permission::create(['name' => 'update customers']);
        
        Permission::create(['name' => 'list contacts']);
        Permission::create(['name' => 'show contacts']);
        Permission::create(['name' => 'store contacts']);
        Permission::create(['name' => 'destroy contacts']);
        Permission::create(['name' => 'update contacts']);
        
        Permission::create(['name' => 'list groups']);
        Permission::create(['name' => 'show groups']);
        Permission::create(['name' => 'store groups']);
        Permission::create(['name' => 'destroy groups']);
        Permission::create(['name' => 'update groups']);
        
        Permission::create(['name' => 'list systems']);
        Permission::create(['name' => 'show systems']);
        Permission::create(['name' => 'store systems']);
        Permission::create(['name' => 'destroy systems']);
        Permission::create(['name' => 'update systems']);
        
        Permission::create(['name' => 'list users']);
        Permission::create(['name' => 'show users']);
        Permission::create(['name' => 'store users']);
        Permission::create(['name' => 'destroy users']);
        Permission::create(['name' => 'update users']);

        Permission::create(['name' => 'show reports systems']);
        Permission::create(['name' => 'show messages customer tickets']);
        Permission::create(['name' => 'show status reply customer tickets']);
        Permission::create(['name' => 'show status reply admin tickets']);

        $role = Role::where(['name' => 'Administrador'])->first();

        $role->givePermissionTo(Permission::whereNotIn("name", [
            "portal customer",
            "only assigned tickets"
        ])->get());

        $role = Role::where(['name' => 'Desarrollador'])->first();
        $role->givePermissionTo([
            'portal admin',
            'list tickets',
            'show tickets',
            'show contacts',
            'reply tickets',
            'details tickets',
            'details contact tickets',
            'details tracked tickets',
            'update tracked tickets',
            'update status tickets',
            'show status reply admin tickets',
            'only assigned tickets',
        ]);

        $role = Role::where(['name' => 'Cliente'])->first();
        $role->givePermissionTo([
            'portal customer',
            'list tickets',
            "show tickets",
            "store tickets",
            "reply tickets",
            "reply customer tickets",
            "details tickets"
        ]);


    }
}
