<?php

namespace Database\Seeders;

use App\Models\TypeTicket;
use Illuminate\Database\Seeder;

class TypeTicketsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TypeTicket::insert([
            [
                'description' => 'Pregunta'
            ],
            [
                'description' => 'Solicitud'
            ],
            [
                'description' => 'Incidencia'
            ],
        ]);
    }
}
