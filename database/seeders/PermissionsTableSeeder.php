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
        Permission::create(['name' => 'register provider']); 
        Permission::create(['name' => 'list providers status']); 
        Permission::create(['name' => 'list providers']); 
        Permission::create(['name' => 'edit providers']); 
        Permission::create(['name' => 'show providers']); 
        Permission::create(['name' => 'show providers resume']);  
        Permission::create(['name' => 'create providers sap']); 
        Permission::create(['name' => 'edit providers sap']); 
        Permission::create(['name' => 'show providers sap']);  
        Permission::create(['name' => 'show providers authorizes']); 
        Permission::create(['name' => 'download providers xlsx']); 

        // actions 
        Permission::create(['name' => 'approve documents']);  
        Permission::create(['name' => 'approve providers edit']); 
        Permission::create(['name' => 'contract providers']);  
        Permission::create(['name' => 'inactive providers']); 
        Permission::create(['name' => 'authorize providers sap']); 

        Permission::create(['name' => 'create requirements']);
        Permission::create(['name' => 'show requirements']);
        Permission::create(['name' => 'edit requirements']);
        Permission::create(['name' => 'delete requirements']);
        Permission::create(['name' => 'list requirements']);

        Permission::create(['name' => 'list guides']);
        Permission::create(['name' => 'edit guides']);

        Permission::create(['name' => 'list users']);
        Permission::create(['name' => 'edit users']);
     
        
    }
}
