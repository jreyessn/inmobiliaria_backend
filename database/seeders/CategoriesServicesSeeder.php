<?php

namespace Database\Seeders;

use App\Models\Categories\CategoriesServices;
use Illuminate\Database\Seeder;

class CategoriesServicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CategoriesServices::insert([
            [
                "name" => "Matenimiento Preventivo",
            ],
            [
                "name" => "Matenimiento Correctivo",
            ],
        ]);
    }
}
