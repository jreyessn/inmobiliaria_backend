<?php

namespace Database\Seeders;

use App\Models\Currency\Currency;
use Illuminate\Database\Seeder;

class CurrenciesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Currency::create([
            "name"      => "Dolar Estadounidense",
            "symbol"    => "$",
            "main"      => 0,
            "rate"      => 0,
            "operator"  => "*"
        ]);
        
        Currency::create([
            "name"      => "Peso Dominicano",
            "symbol"    => "RD$",
            "main"      => 1,
            "rate"      => 0,
            "operator"  => "/"
        ]);

    }
}
