<?php 

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class SupervisorMonthReport implements WithMultipleSheets
{
    
    private $result;
    
    function __construct($result)
    {
        $this->result = $result;   
    }

    public function sheets(): array
    {

        return array_map(function($item){

            return new SupervisorMonthSheet($item);

        }, $this->result);

    }
}
