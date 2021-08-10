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
