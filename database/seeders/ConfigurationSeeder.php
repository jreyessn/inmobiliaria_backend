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
               "key"   => "system_name",
               "value" => "Sistema de Inmuebles"
            ],
            [
               "key"   => "business_name",
            ],
            [
               "key"   => "business_address",
            ],
            [
               "key"   => "business_identify",
            ],
            [
               "key"   => "business_contact",
            ],
            [
               "key"   => "business_phone",
            ],
            [
               "key"   => "logo",
            ],
        ]);
    }
}
