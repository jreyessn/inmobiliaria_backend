<?php

namespace App\Http\Controllers\Vehicle;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportsVehicleController extends Controller
{

    function __construct()
    {
        
    }
    
    /**
     * Reporte de combustible por mes
     */
    function fuelsMonths(Request $request)
    {
        $request->validate([
            "year"    => "required",
            "format"  => "nullable",
            "vehicle" => "nullable" //example: 1,2,34
        ]);

        switch ($request->format) {
            // case 'excel':
            //     $data = $this->ServiceRepositoryEloquent->where("status", 1)->get();

            //     return Excel::download(
            //         new ViewExport ([
            //             'data' => [
            //                 "data"  => $data,
            //                 "since" => $request->since? Carbon::parse($request->since) : null,
            //                 "until" => $request->until? Carbon::parse($request->until) : null,
            //             ],
            //             'view' => 'reports.excel.reports_services'
            //         ]),
            //         'reports_services.xlsx'
            //     );
            // break;
                
            // case 'pdf':
            //     $data = $this->ServiceRepositoryEloquent->where("status", 1)->get();

            //     return PDF::loadView('reports/pdf/reports_services', [
                    
            //         "data"  => $data,
            //         "since" => $request->since? Carbon::parse($request->since) : null,
            //         "until" => $request->until? Carbon::parse($request->until) : null,

            //     ])->stream('reports_services.pdf');

            // break;

            default:    
                

                return ["a" => 1];
            break;
        }

    } 

}
