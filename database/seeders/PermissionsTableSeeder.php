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
        Role::where(['name' => 'Repartidor'])->first()->syncPermissions([]);
        Role::where(['name' => 'Cliente'])->first()->syncPermissions([]);
        Role::where(['name' => 'Corte'])->first()->syncPermissions([]);
        Role::where(['name' => 'Asignacion'])->first()->syncPermissions([]);

        Permission::query()->delete();

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

        Permission::create(['name' => 'redirect to scanner']);

        Permission::create(['name' => 'scanner feature']);
        Permission::create(['name' => 'scanner info customer']);
        Permission::create(['name' => 'scanner use coupons']);
        Permission::create(['name' => 'scanner request coupon']);
        Permission::create(['name' => 'scanner visit register']);

        $role = Role::where(['name' => 'Administrador'])->first();
        $role->givePermissionTo( 
            Permission::whereNotIn("name", [
                "redirect to scanner"
            ])->get() 
        );
        
        $role = Role::where(['name' => 'Promotor de Ventas'])->first();
        $role->givePermissionTo([
            'redirect to scanner',
            'scanner feature',
            'scanner info customer',
            'scanner request coupon',
            'scanner visit register',
        ])->get();

        $role = Role::where(['name' => 'Repartidor'])->first();
        $role->givePermissionTo([
            'redirect to scanner',
            'scanner feature',
            'scanner info customer',
            'scanner use coupons',
        ]);
        
        $role = Role::where(['name' => 'Corte'])->first();
        $role->givePermissionTo([
            'reports',
            'stadistics',
            'list users',
            'show users',
            'list coupons',
            'show coupons',
            'list customers',
            'show customers',
            'list coupons request',
            'show coupons request',
            'scanner feature',
            'scanner info customer',
        ]);

        $role = Role::where(['name' => 'Asignacion'])->first();
        $role->givePermissionTo(
            Permission::whereNotIn("name", [
                "redirect to scanner",
                "list users",
                "show users",
                "store users",
                "update users",
                "destroy users",
            ])->get() 
        );
        
        $role = Role::where(['name' => 'Cliente'])->first();
        $role->givePermissionTo([
            'list coupons',
            'show coupons',
        ]);
    }
}
