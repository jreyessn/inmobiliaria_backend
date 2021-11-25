<?php

namespace App\Imports;

use App\Models\Customer\Customer;
use App\Repositories\Customer\CustomerRepositoryEloquent;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class CustomersImport implements ToCollection
{
    
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        
        $rows = $this->filterEmptyRows($collection);

        Validator::make(["data" => $rows->toArray()], [
            'data' => 'required',
        ],
        [
            "data.required" => "Debe llenar las celdas de al menos una fila del excel",
        ]
        )->validate();

        foreach ($rows as $key => $row) {
            
            $numberRow = $key + 3;
            Validator::make($row, 
                [
                    'tradename'      => "sometimes|max:100",
                    'business_name'  => "required|max:200",
                    'price_coupon'   => 'required|numeric',
                    'street'         => 'required',
                    'street_number'  => 'nullable',
                    'colony'         => 'nullable',
                    'phone'          => 'nullable',
                    'email'          => 'nullable',
                ],
                [],
                [
                    'tradename'      => "Nombre Comercial [Fila {$numberRow}]",
                    'business_name'  => "Razón Social [Fila {$numberRow}]",
                    'price_coupon'   => "Precio de Cupón [Fila {$numberRow}]",
                    'street'         => "Calle [Fila {$numberRow}]",
                    'street_number'  => "Número de Calle [Fila {$numberRow}]",
                    'colony'         => "Colonia [Fila {$numberRow}]",
                    'phone'          => "Teléfono [Fila {$numberRow}]",
                    'email'          => "Correo [Fila {$numberRow}]",
                ]
            )->validate();

            Customer::create($row);
        }

    }

    private function filterEmptyRows(Collection $collection)
    {
        return $collection->filter(function($value, $key){

            if($key < 2){
                return false;
            }

            return !$value->every(function($value){
                return is_null($value);
            });

        })->map(function($value){
            return [
                "tradename"     => $value[0],
                "business_name" => $value[1],
                "price_coupon"  => $value[2],
                "street"        => $value[3],
                "street_number" => $value[4],
                "colony"        => $value[5],
                "phone"         => $value[6],
                "email"         => $value[7],
                "coupons"       => 0
            ];
        })->values();
    }
}
