<?php

namespace Database\Seeders;

use App\Models\Priority;
use Illuminate\Database\Seeder;

class PrioritiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Priority::insert([
            [
                'description' => 'Baja',
                'color' => '#a0d76a',
                'default' => 1
            ],
            [
                'description' => 'Media',
                'color' => '#4da1ff',
                'default' => 0
            ],
            [
                'description' => 'Alta',
                'color' => '#ffd012',
                'default' => 0
            ],
            [
                'description' => 'Urgente',
                'color' => '#ff5959',
                'default' => 0
            ],
        ]);
    }
}
