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
            "email" => "developer@gmail.com",
            "password" => "1234"
        ]);
        $user->assignRole(['Administrador']);

        $user = User::create([
            'name' => "Jesus Basurto",
            'username' => "JeanlogisticsAdmin",
            "email" => "jbasurto@empresainteligente.com",
            "password" => "1234"
        ]);
        $user->assignRole(['Gerente']);

        $user = User::create([
            'name' => "Jesus Basurto",
            'username' => "JeanlogisticsSupervisor",
            "email" => "jbasurto@empresainteligente2.com",
            "password" => "1234"
        ]);
        $user->assignRole(['Supervisor']);


    }
}
