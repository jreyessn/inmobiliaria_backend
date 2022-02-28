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
        Role::where(['name' => 'Chofer'])->first()->syncPermissions([]);

        Permission::query()->delete();

        // ACTIONS GENERAL
        Permission::create(['name' => 'home pwa']);
        Permission::create(['name' => 'system switch']);
        Permission::create(['name' => 'system equipments']);
        Permission::create(['name' => 'system vehicles']);
        
        // FILTERS SELECT
        Permission::create(['name' => 'filter technicians']);
        Permission::create(['name' => 'filter chofers']);

        // MODULES ACTIONS EQUIPMENTS
        Permission::create(['name' => 'reports service']);
        Permission::create(['name' => 'dashboard equipments']);

        Permission::create(['name' => 'list users']);
        Permission::create(['name' => 'store users']);
        Permission::create(['name' => 'update users']);
        Permission::create(['name' => 'destroy users']);

        Permission::create(['name' => 'list tools']);
        Permission::create(['name' => 'list type services equipments']);
        Permission::create(['name' => 'list spare parts']);
        Permission::create(['name' => 'list farms']);
        Permission::create(['name' => 'list areas']);
        Permission::create(['name' => 'list equipment categories']);
        
        Permission::create(['name' => 'list services equipments']);
        Permission::create(['name' => 'calendary services equipments']);
        Permission::create(['name' => 'store services equipments']);
        Permission::create(['name' => 'update services equipments']);
        Permission::create(['name' => 'destroy services equipments']);
        Permission::create(['name' => 'complete services equipments']);

        Permission::create(['name' => 'list equipments']);
        Permission::create(['name' => 'store equipments']);
        Permission::create(['name' => 'update equipments']);
        Permission::create(['name' => 'destroy equipments']);

        // MODULES ACTIONS VEHICLES
        Permission::create(['name' => 'dashboard vehicles']);

        Permission::create(['name' => 'list vehicles']);
        Permission::create(['name' => 'store vehicles']);
        Permission::create(['name' => 'update vehicles']);
        Permission::create(['name' => 'destroy vehicles']);

        Permission::create(['name' => 'list services vehicles']);
        Permission::create(['name' => 'store services vehicles']);
        Permission::create(['name' => 'update services vehicles']);
        Permission::create(['name' => 'destroy services vehicles']);
        Permission::create(['name' => 'complete services vehicles']);
        
        Permission::create(['name' => 'list binnacle']);
        Permission::create(['name' => 'list fuels']);
        Permission::create(['name' => 'list payments']);
        Permission::create(['name' => 'list permissions']);
        Permission::create(['name' => 'list licenses']);
        Permission::create(['name' => 'list type services vehicles']);
        
        Permission::create(['name' => 'reports executive']);
        Permission::create(['name' => 'reports fuel month']);
        Permission::create(['name' => 'reports km month']);
        Permission::create(['name' => 'reports services month']);


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
               'list services equipments',
               'calendary services equipments',
               'complete services equipments',
               'home pwa',
            ])->get() 
        );
        
        
        $role = Role::where(['name' => 'Chofer'])->first();
        $role->givePermissionTo( 
            Permission::whereIn("name", [
               'system vehicles',
               'dashboard vehicles',
               'list services vehicles'
            ])->get() 
        );
        

        
    }
}
