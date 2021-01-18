<?php 

namespace App\Exports;

use App\Models\Visit\Visit;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class VisitReport implements WithMultipleSheets 
{

    private $visit;
    
    function __construct(Visit $visit)
    {
        $this->visit = $visit;   
    }

    public function sheets(): array
    {
        return [
           new VisitSheet($this->visit),
           new MorbilitiesSheet($this->visit->mortalities)
        ];
    }
}
