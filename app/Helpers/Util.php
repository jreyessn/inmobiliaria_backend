<?php

use App\Models\Configuration;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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

if(!function_exists("sanitize_null")){
    function sanitize_null(array $array): array {
       return collect($array)->filter(function($value){
           return $value !== null;
       })->toArray();
    }
}

if(!function_exists("descryptId")){
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
}

if(!function_exists("convertTobase64")){
    function convertTobase64($path){
        $exist = Storage::disk('local')->exists($path);
        if ($exist) {
            $content = Storage::disk('local')->get($path);
            return base64_encode($content);
        }
        return '';
    }
}

if(!function_exists("defaultPreferences")){
    function defaultPreferences(){
       return [
           "layout" => "classic",
           "scheme" => "light"
       ];
    }
}

if(!function_exists("sum_amount_tax")){
    /**
     * Obtiene valor del monto con el impuesto
     * 
     * @param int $amount Monto
     * @param int $tax Impuesto reflejado en entero (0 - 100)
     */
    function sum_amount_tax($amount, $tax){
        return (float) number_format((float) $amount * (((float) $tax / 100) + 1), 2);
    }
}

