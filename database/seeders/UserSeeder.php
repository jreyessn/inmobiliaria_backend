<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        User::query()->delete();

        $user = User::create([
            'name' => "Juan Reyes",
            'username' => "developer",
            "email" => "juan.reyes@jeanlogistics.com",
            "password" => "1234"
        ]);
        $user->assignRole(['Administrador']);

        $user = User::create([
            'name' => "Admin",
            'username' => "admin",
            "email" => "admin@invalid.com",
            "password" => "1234"
        ]);
        $user->assignRole(['Administrador']);


    }
}
