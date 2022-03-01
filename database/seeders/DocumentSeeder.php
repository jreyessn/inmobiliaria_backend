<?php

namespace Database\Seeders;

use App\Models\Sale\Document;
use Illuminate\Database\Seeder;

class DocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Document::create(["name" => "NOTA DE VENTA", "code" => "NV"]);
    }
}
