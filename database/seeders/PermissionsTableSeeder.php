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

        Permission::query()->delete();

        $role = Role::where(['name' => 'Administrador'])->first();
        $role->givePermissionTo( 
            Permission::get() 
        );
        

        
    }
}
