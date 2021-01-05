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

        Permission::create(['name' => 'dashboard']); 

        Permission::create(['name' => 'show vists']); 
        Permission::create(['name' => 'store vists']); 
        Permission::create(['name' => 'edit vists']); 
        Permission::create(['name' => 'destroy vists']);

        Permission::create(['name' => 'show farms']); 
        Permission::create(['name' => 'store farms']); 
        Permission::create(['name' => 'edit farms']); 
        Permission::create(['name' => 'destroy farms']); 

        $superadmin = Role::create(['name' => 'Administrador']);
        $superadmin->givePermissionTo(Permission::all());

        $gerente = Role::create(['name' => 'Gerente']);
        $gerente->givePermissionTo(Permission::all());

        $supervisor = Role::create(['name' => 'Supervisor']);
        $supervisor->givePermissionTo(Permission::all());
        
    }
}
