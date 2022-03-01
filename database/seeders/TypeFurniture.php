<?php

namespace Database\Seeders;

use App\Models\Furniture\TypeFurniture as FurnitureTypeFurniture;
use Illuminate\Database\Seeder;

class TypeFurniture extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        FurnitureTypeFurniture::create([ "name" => "Casa" ]);
        FurnitureTypeFurniture::create([ "name" => "Apartamento" ]);
    }
}
