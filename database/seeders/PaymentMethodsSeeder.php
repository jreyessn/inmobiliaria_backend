<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                "description" => "Depósito Bancario"
            ],
            [
                "description" => "Transferencia"
            ],
            [
                "description" => "Efectivo"
            ],
            [
                "description" => "Cheque"
            ],
            [
                "description" => "Tarjeta de Débito"
            ],
            [
                "description" => "Tarjeta de Crédito"
            ],
        ];

        foreach($data as $item){
            PaymentMethod::create($item);
        }
    }
}
