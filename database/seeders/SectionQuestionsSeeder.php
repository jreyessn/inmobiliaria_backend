<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Question\SectionsQuestion;

class SectionQuestionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SectionsQuestion::insert([
            [
                'description' => 'MEDIO AMBIENTE',
                'order' => 1
            ],
            [
                'description' => 'MANEJO DE MORTALIDAD',
                'order' => 2
            ],
            [
                'description' => 'MANEJO CORRECTO DE AGUA',
                'order' => 3
            ],
            [
                'description' => 'MANEJO APROPIADO DE ALIMENTO',
                'order' => 4
            ],
            [
                'description' => 'AMBIENTE ADECUADO DE EDIFICIO',
                'order' => 5
            ],
            [
                'description' => 'MANEJO ADECUADO DE CERDOS',
                'order' => 6
            ],
            [
                'description' => 'EQUIPOS FUNCIONANDO CORRECTAMENTE',
                'order' => 7
            ],
            [
                'description' => 'CONTROL REGISTROS Y FORMATOS',
                'order' => 8
            ],
            [
                'description' => 'PRÁCTICAS DE BIOSEGURIDAD',
                'order' => 9
            ],
            [
                'description' => 'LIMPIEZA Y ORDEN DE GRANJA',
                'order' => 10
            ],
            [
                'description' => 'IMPLEMENTACIÓN DE PROTOCOLOS',
                'order' => 11
            ],
        ]);
    }
}
