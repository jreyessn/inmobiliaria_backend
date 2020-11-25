<?php

namespace Database\Seeders;

use App\Models\TypeProvider;
use Illuminate\Database\Seeder;

class TypeProvidersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TypeProvider::insert([
            [
                'description' => 'Materiales para Producción Planta'
            ],
            [
                'description' => 'Materiales para Producción Granjas'
            ],
            [
                'description' => 'Materiales / Equipos para Mantenimiento'
            ],
            [
                'description' => 'Materiales Administrativos'
            ],
            [
                'description' => 'Servicios / Trámites Administrativos'
            ],
            [
                'description' => 'Servicios de Mantenimiento'
            ],
            [
                'description' => 'Proveedor de Productos para Venta en Tiendas'
            ],
        ]);
    }
}
