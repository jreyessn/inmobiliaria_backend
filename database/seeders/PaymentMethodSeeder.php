<?php

namespace Database\Seeders;

use App\Models\Sale\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PaymentMethod::create(["name" => "EFECTIVO", "type" => "CASH"]);
        PaymentMethod::create(["name" => "DEPÓSITO", "type" => "BANK"]);
        PaymentMethod::create(["name" => "CHEQUE", "type" => "CASH"]);
        PaymentMethod::create(["name" => "NOTA DE CRÉDITO", "type" => "CASH"]);
        PaymentMethod::create(["name" => "TARJETA", "type" => "BANK"]);
        PaymentMethod::create(["name" => "TRANSFERENCIA", "type" => "BANK"]);
    }
}
