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
               "key"   => "business_email",
               "value" => null
            ],
            [
               "key"   => "business_phone",
               "value" => null
            ],
            [
               "key"   => "logo_white",
               "value" => null
            ],
            [
               "key"   => "logo_dark",
               "value" => null
            ],
            [
               "key"   => "country_id",
               "value" => 62 // DO
            ],
            [
               "key"   => "city_id",
               "value" => null
            ],
            [
               "key"   => "receipt_name",
               "value" => null
            ],
            [
               "key"   => "receipt_footer",
               "value" => null
            ],
        ]);
    }
}
