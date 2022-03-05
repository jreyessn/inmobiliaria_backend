<?php

namespace Database\Seeders;

use App\Models\Furniture\MeasureUnit;
use Illuminate\Database\Seeder;

class MeasureUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MeasureUnit::create([
            "name"        => "m²",
            "description" => "Metros Cuadrados"
        ]);
    }
}
