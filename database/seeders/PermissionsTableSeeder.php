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
        Role::where(['name' => 'Promotor de Ventas'])->first()->syncPermissions([]);
        Role::where(['name' => 'Chofer'])->first()->syncPermissions([]);
        Role::where(['name' => 'Cliente'])->first()->syncPermissions([]);

        Permission::query()->delete();

        Permission::create(['name' => 'dashboard']);
        Permission::create(['name' => 'reports']);
        Permission::create(['name' => 'stadistics']);
        
        Permission::create(['name' => 'list users']);
        Permission::create(['name' => 'show users']);
        Permission::create(['name' => 'store users']);
        Permission::create(['name' => 'destroy users']);
        Permission::create(['name' => 'update users']);
        
        Permission::create(['name' => 'list customers']);
        Permission::create(['name' => 'show customers']);
        Permission::create(['name' => 'store customers']);
        Permission::create(['name' => 'destroy customers']);
        Permission::create(['name' => 'update customers']);

        Permission::create(['name' => 'list coupons']);
        Permission::create(['name' => 'show coupons']);
        Permission::create(['name' => 'store coupons']);
        Permission::create(['name' => 'destroy coupons']);
        Permission::create(['name' => 'update coupons']);

        Permission::create(['name' => 'list coupons request']);
        Permission::create(['name' => 'show coupons request']);
        Permission::create(['name' => 'store coupons request']);
        Permission::create(['name' => 'destroy coupons request']);
        Permission::create(['name' => 'update coupons request']);
        Permission::create(['name' => 'approve coupons request']);

        $role = Role::where(['name' => 'Administrador'])->first();
        $role->givePermissionTo(Permission::all())->get();
        
        $role = Role::where(['name' => 'Promotor de Ventas'])->first();
        $role->givePermissionTo(Permission::all())->get();

        $role = Role::where(['name' => 'Chofer'])->first();
        $role->givePermissionTo([
            'dashboard',
            'list customers',
            'show customers',
            "list coupons",
            'show coupons',
            'store coupons request',
            'show coupons request',
        ]);
        
        $role = Role::where(['name' => 'Cliente'])->first();
        $role->givePermissionTo([
            'show coupons',
        ]);
    }
}
