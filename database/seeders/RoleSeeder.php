<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create(['name' => 'Administrador']);

        Role::create(['name' => 'Promotor de Ventas']);
        
        Role::create(['name' => 'Repartidor']);
       
        Role::create(['name' => 'Cliente']);
    }
}
