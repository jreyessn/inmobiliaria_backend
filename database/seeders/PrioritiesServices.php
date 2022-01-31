<?php

namespace Database\Seeders;

use App\Models\PrioritiesService;
use Illuminate\Database\Seeder;

class PrioritiesServices extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PrioritiesService::insert([
            [
                "name"  => "Normal",
                "orden" => 1,
                "color" => "#868e96",
            ],
            [
                "name"  => "Urgente",
                "orden" => 2,
                "color" => "#ff586b",
            ],
        ]);
    }
}
