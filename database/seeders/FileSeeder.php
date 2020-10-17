<?php

namespace Database\Seeders;

use App\Models\File;
use Illuminate\Database\Seeder;

class FileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        File::query()->delete();
           
        $data = [
            [
                "title" => "Consideraciones a Proveedores Generales",
                "description" => "This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.",
                "name" => "example.pdf",
                "type" => "requirements"
            ],
            [
                "title" => "Requisito como Extranjeros",
                "description" => "This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.",
                "name" => "example.pdf",
                "type" => "requirements"
            ],
            [
                "title" => "Requisito para clientes",
                "description" => "This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.",
                "name" => "example.pdf",
                "type" => "requirements"
            ],
            [
                "title" => "Términos y Condiciones",
                "description" => "This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.",
                "name" => "example.pdf",
                "type" => "terminos"
            ],
         
        ];

        foreach ($data as $value) {
            File::create($value);
        }
    }
}
