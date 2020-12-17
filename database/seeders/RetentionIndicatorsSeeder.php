<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RetentionIndicator;

class RetentionIndicatorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RetentionIndicator::query()->delete();
        
        $data = [
            [
                "type" => "S1",
                "indicator" => "2",
                "description" => "ISR Retenido por Arrendamiento 10%",
            ],            
            [
                "type" => "S2",
                "indicator" => "1",
                "description" => "ISR Retenido por Honorarios 10%",
            ],            
            [
                "type" => "V1",
                "indicator" => "1",
                "description" => "IVA Retenido por Fletes 4%",
            ],            
            [
                "type" => "V2",
                "indicator" => "1",
                "description" => "IVA Retenido por Arrendamiento 10.66%",
            ],            
            [
                "type" => "V2",
                "indicator" => "2",
                "description" => "IVA Retenido por Arrendamiento 7.33%",
            ],            
            [
                "type" => "V3",
                "indicator" => "1",
                "description" => "IVA Retenido por Honorarios 10.66%",
            ],            
            [
                "type" => "V3",
                "indicator" => "2",
                "description" => "IVA Retenido por Honorarios 7.33%",
            ],            
            [
                "type" => "V6",
                "indicator" => "1",
                "description" => "IVA Por Venta de desechos (Ret. 100%)",
            ],            
            [
                "type" => "V5",
                "indicator" => "1",
                "description" => "IVA Servicios 6%",
            ],            
        ];
        
        foreach ($data as $value) {
            RetentionIndicator::create($value);
        }

    }
}
