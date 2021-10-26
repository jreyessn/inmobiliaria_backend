<?php

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
 
if (! function_exists('current_role')) {
    function current_role($attribute = '')
    {
        $role = request()->user()->roles[0];

        if($attribute == '')
            return $role;
        return $role->$attribute;
    }
}
 
if (! function_exists('month_en')) {
    function month_en($attribute = '')
    {
        $meses = array(
            "enero" => "January",
            "febrero" => "February",
            "marzo" => "March",
            "abril" => "April",
            "mayo" => "May",
            "junio" => "June",
            "julio" => "July",
            "agosto" => "August",
            "septiembre" => "September",
            "octubre" => "October",
            "noviembre" => "November",
            "diciembre" => "December"
        );

        $key = strtolower($attribute);
        $diff = array_key_exists($key, $meses);

        return $diff === false? false : $meses[$key];
        
    }
}

if(!function_exists("decimal")){

    function decimal($num, $points = 2){
        return (float) number_format($num, $points, '.', '');
    }
}

if(!function_exists("last_query")){

    function last_query(){
        $queries = DB::getQueryLog();

        if(count($queries) == 0)
            return null;

        $last_query = end($queries);

        if(array_key_exists("query", $last_query) && array_key_exists("bindings", $last_query)){
            
            return vsprintf(str_replace('?', '`%s`', $last_query['query']), $last_query['bindings']);
        
        }

        return null;
    }
}

if(!function_exists("format_ceros")){

    function format_ceros($value = 0, $zeros = 4){
        return str_pad($value, $zeros, "0", STR_PAD_LEFT);
    }
}

if(!function_exists("getMovement")){

    function getMovement($value = 1){

       if($value == 1){
           return "Venta";
       }
       
       if($value == 2){
           return "Devolución";
       }

       if($value == 3){
           return "Entrega";
       }
       
       if($value == 4){
           return "Ajuste";
       }


    }
}

if(!function_exists("getIo")){

    function getIo($type_movement){

       if($type_movement == "Venta"){
           return 1;
       }

       if($type_movement == "Devolución"){
           return 1;
       }

       if($type_movement == "Entrega"){
           return 2;
       }

       return 1;
       
    }
}

if(!function_exists("currency")){

    function currency(){
      return "$";
    }
}

if(!function_exists("period_months")){

    function period_months($since, $until){

        $since  = \Carbon\Carbon::parse($since)->format("Y-m-d");
        $until  = \Carbon\Carbon::parse($until)->format("Y-m-d");

        $period = \Carbon\CarbonPeriod::create($since, '1 month', $until);
        $months = [];

        foreach ($period as $dt) {
            array_push($months, [
                'month_year' => $dt->format("Y-m"),
                'first_day' => $dt->format("Y-m-01"),
                'end_day' =>  $dt->format("Y-m-{$dt->endOfMonth()->format('d')}"),
                'description' => ucwords("{$dt->year} - {$dt->monthName}")
            ]);
       }

       return $months;
    }
}

function descryptId($str){
    if(strlen($str) > 100){
        try {
            return Crypt::decrypt($str);
        } catch (\Throwable $th) {
            return $str;
        }
    }

    return $str;
}

