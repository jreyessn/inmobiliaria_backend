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

        Role::where(['name' => 'Administrador'])->first()->syncPermissions([]);
        Role::where(['name' => 'Técnico'])->first()->syncPermissions([]);

        Permission::query()->delete();

        // ACTIONS GENERAL
        Permission::create(['name' => 'home pwa']);
        Permission::create(['name' => 'system switch']);
        Permission::create(['name' => 'system equipments']);
        Permission::create(['name' => 'system vehicles']);
        
        // FILTERS SELECT
        Permission::create(['name' => 'filter technicians']);

        // MODULES ACTIONS
        Permission::create(['name' => 'reports service']);
        Permission::create(['name' => 'dashboard']);

        Permission::create(['name' => 'list users']);
        Permission::create(['name' => 'store users']);
        Permission::create(['name' => 'update users']);
        Permission::create(['name' => 'destroy users']);

        Permission::create(['name' => 'list tools']);
        Permission::create(['name' => 'list type services']);
        Permission::create(['name' => 'list spare parts']);
        Permission::create(['name' => 'list farms']);
        Permission::create(['name' => 'list areas']);
        Permission::create(['name' => 'list equipment categories']);
        
        Permission::create(['name' => 'list services']);
        Permission::create(['name' => 'calendary services']);
        Permission::create(['name' => 'store services']);
        Permission::create(['name' => 'update services']);
        Permission::create(['name' => 'destroy services']);
        Permission::create(['name' => 'complete services']);

        Permission::create(['name' => 'list equipments']);
        Permission::create(['name' => 'store equipments']);
        Permission::create(['name' => 'update equipments']);
        Permission::create(['name' => 'destroy equipments']);

        $role = Role::where(['name' => 'Administrador'])->first();
        $role->givePermissionTo( 
            Permission::whereNotIn("name", [
               'home pwa',
               'system equipments',
               'system vehicles',
            ])->get() 
        );
        
        $role = Role::where(['name' => 'Técnico'])->first();
        $role->givePermissionTo( 
            Permission::whereIn("name", [
               'system equipments',
               'reports service',
               'list services',
               'calendary services',
               'complete services',
               'home pwa',
            ])->get() 
        );
        
        
        $role = Role::where(['name' => 'Chofer'])->first();
        $role->givePermissionTo( 
            Permission::whereIn("name", [
               'system vehicles',
            ])->get() 
        );
        

        
    }
}
