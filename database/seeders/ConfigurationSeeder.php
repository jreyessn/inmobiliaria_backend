<?php

namespace Database\Seeders;

use App\Models\Configuration;
use Illuminate\Database\Seeder;

class ConfigurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Configuration::insert([
            [
               "key"   => "name",
               "value" => "Sistema de Inmuebles"
            ],
            [
               "key"   => "business_name",
               "value" => null
            ],
            [
               "key"   => "business_address",
               "value" => null
            ],
            [
               "key"   => "business_identify",
               "value" => null
            ],
            [
               "key"   => "business_contact",
               "value" => null
            ],
            [
               "key"   => "business_phone",
               "value" => null
            ],
            [
               "key"   => "logo",
               "value" => null
            ],
            [
               "key"   => "country_id",
               "value" => 62 // DO
            ]
        ]);
    }
}
