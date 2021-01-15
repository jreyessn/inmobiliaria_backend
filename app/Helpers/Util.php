<?php

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

    function scoreTotalQuestions()
    {
        return DB::table('questions')->selectRaw("sum(max_score) as max_score")->first()->max_score ?? 0;
    }