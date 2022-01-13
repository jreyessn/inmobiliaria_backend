<?php

namespace Database\Seeders;

use App\Models\Categories\CategoriesServices;
use App\Models\Services\CategoriesService;
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
        CategoriesService::insert([
            [
                "name" => "Matenimiento Preventivo",
            ],
            [
                "name" => "Matenimiento Correctivo",
            ],
        ]);
    }
}
