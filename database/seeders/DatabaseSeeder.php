<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    
    public function run()
    {

        $this->call([
            RoleSeeder::class,
            PermissionsTableSeeder::class,
            UserSeeder::class,
            ConfigurationSeeder::class,
            PaymentMethodsSeeder::class,
        ]);

    }
}
