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

function getCode(int $number, $format = 5)
{
    return str_pad($number, $format, 0, STR_PAD_LEFT);
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

function decimal($num, $points = 2){
    return (float) number_format($num, $points, '.', '');
}