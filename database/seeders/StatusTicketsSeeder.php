<?php

namespace Database\Seeders;

use App\Models\StatusTicket;
use Illuminate\Database\Seeder;

class StatusTicketsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        StatusTicket::insert([
            [
                'description' => 'Abierto',
                'can_close' => 0,
                'default' => 1
            ],
            [
                'description' => 'En Proceso',
                'can_close' => 0,
                'default' => 0
            ],
            [
                'description' => 'Resuelto',
                'can_close' => 0,
                'default' => 0
            ],
            [
                'description' => 'Cerrado',
                'can_close' => 1,
                'default' => 0
            ],
            [
                'description' => 'Esperando Cliente',
                'can_close' => 0,
                'default' => 0
            ],
        ]);
    }
}
