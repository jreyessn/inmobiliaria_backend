<?php

namespace App\Http\Controllers\Vehicle;

use App\Criteria\VehicleCriteria;
use App\Criteria\VehicleReportsCriteria;
use App\Exports\ViewExport;
use App\Http\Controllers\Controller;
use App\Repositories\Vehicle\FuelRepositoryEloquent;
use App\Repositories\Vehicle\VehicleRepositoryEloquent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade as PDF;
use Maatwebsite\Excel\Facades\Excel;

class ReportsVehicleController extends Controller
{

    private $VehicleRepositoryEloquent;

    private $FuelRepositoryEloquent;

    function __construct(
        VehicleRepositoryEloquent $VehicleRepositoryEloquent,
        FuelRepositoryEloquent $FuelRepositoryEloquent
    )
    {
        $this->VehicleRepositoryEloquent = $VehicleRepositoryEloquent;
        $this->FuelRepositoryEloquent    = $FuelRepositoryEloquent;
    }

    /**
     * Reporte ejecutivo (combinación de reportes)
     */
    public function executiveVehicles(Request $request)
    {
        $request->validate([
            "year"       => "required",
            "format"     => "nullable",
            "vehicle_id" => "nullable" //example: 1,2,34
        ]);

        $request->merge(["executive" => true]);

        $data["services_month"] = $this->servicesMonth($request);
        $data["km_month"]       = $this->KmMonths($request);
        $data["fuels_month"]    = $this->fuelsMonths($request);
        $data["year"]           = $request->year;

        switch ($request->format) {
            case 'excel':
  
                
            break;
                
            case 'pdf':

                return PDF::loadView('reports/pdf/reports_executive_vehicle', $data)
                           ->stream('reports_executive_vehicle.pdf');

            break;

            default:    
                return $data;
            break;
        }
    }

    /**
     * Reporte de servicios por mes
     */
    public function servicesMonth(Request $request)
    {
        $request->validate([
            "year"       => "required",
            "format"     => "nullable",
            "vehicle_id" => "nullable" //example: 1,2,34
        ]);

        $period = \Carbon\CarbonPeriod::create("{$request->year}-01-01", '1 month', "{$request->year}-12-01");
        $months = collect([]);

        foreach ($period as $dt) {
            $months->push([
                'month_year'  => $dt->format("Y-m"),
                'since'       => $dt->format("Y-m-01"),
                'until'       => $dt->format("Y-m-{$dt->endOfMonth()->format('d')}"),
                'description' => ucwords("{$dt->monthName}"),
                'data'        => collect([]),
                'total'       => (object) [
                    "services" => 0,
                    "amount"   => 0,
                ]
            ]);
       }

        $this->VehicleRepositoryEloquent->pushCriteria(VehicleReportsCriteria::class);

        switch ($request->format) {
            case 'excel':
                $vehicles = $this->VehicleRepositoryEloquent->whereHas("services", function($query){
                    $query->where("status", 1);
                })->get();
                $months   = $this->mapKmTraveled($months, $vehicles);
                $totals   = $this->mapTotals($months, ["services", "amount"]);

                return Excel::download(
                    new ViewExport ([
                        'data' => [
                            "rows"    => $vehicles,
                            "columns" => $months,
                            "totals"  => $totals,
                            "year"    => $request->year,
                        ],
                        'view' => 'reports.excel....'
                    ]),
                    '....xlsx'
                );
            break;
                
            case 'pdf':
                $vehicles = $this->VehicleRepositoryEloquent->whereHas("services", function($query){
                    $query->where("status", 1);
                })->get();
                $months   = $this->mapServicesMonth($months, $vehicles);
                $totals   = $this->mapTotals($months, ["services", "amount"]);
                $params   = [
                    "rows"    => $vehicles,
                    "columns" => $months,
                    "totals"  => $totals,
                    "year"    => $request->year,
                ];

                if($request->executive){
                    return $params;
                }

                return PDF::loadView('reports/pdf/reports_services_month', $params)
                           ->stream('reports_services_month.pdf');

            break;

            default:    
                $vehicles = $this->VehicleRepositoryEloquent->whereHas("services", function($query){
                    $query->where("status", 1);
                })->paginate();

                $months   = $this->mapServicesMonth($months, $vehicles->items());
                $totals   = $this->mapTotals($months, ["services", "amount"]);

                return [
                   "rows"    => $vehicles,
                   "columns" => $months,
                   "totals"  => $totals
                ];

            break;
        }

    }


    /**
     * Mapea los servicios por mes
     * 
     * @param $months Meses
     * @param $vehicles Colección de vehiculos
     */
    private function mapServicesMonth($months, $vehicles)
    {   
        foreach ($months as $month) {
            foreach ($vehicles as $vehicle) {
                
                $totalServices = $this->totalServicesMonth($vehicle, $month);

                $month["data"]->push([
                    'vehicle_id'    => $vehicle->id,
                    'services'      => $totalServices["services"],
                    'amount'        => $totalServices["amount"],
                ]);
            }

            $month["total"]->services = $month["data"]->reduce(function($a, $b){
                return $a + $b["services"];
            }, 0);

            $month["total"]->amount = $month["data"]->reduce(function($a, $b){
                return $a + $b["amount"];
            }, 0);
        }

        return $months;
    }

    /**
     * Calcula el total servicios por mes
     * 
     * @param $vehicle Unidad
     * @param $month[since] Dia desde del mes
     * @param $month[until] Dia hasta del mes
     */
    private function totalServicesMonth($vehicle, $month){
        $data           = [];

        $data["services"] = $vehicle->services()
                                    ->where("status", 1)
                                    ->whereBetween("event_date", [$month["since"], $month["until"]])
                                    ->count(); 
  
        $data["amount"]   = $vehicle->services()
                                    ->where("status", 1)
                                    ->whereBetween("event_date", [$month["since"], $month["until"]])
                                    ->sum("amount"); 
                 
        return $data;
    }


    /**
     * Reporte de KM por mes
     */
    public function KmMonths(Request $request)
    {
        $request->validate([
            "year"       => "required",
            "format"     => "nullable",
            "vehicle_id" => "nullable" //example: 1,2,34
        ]);

        $period = \Carbon\CarbonPeriod::create("{$request->year}-01-01", '1 month', "{$request->year}-12-01");
        $months = collect([]);

        foreach ($period as $dt) {
            $months->push([
                'month_year'  => $dt->format("Y-m"),
                'since'       => $dt->format("Y-m-01"),
                'until'       => $dt->format("Y-m-{$dt->endOfMonth()->format('d')}"),
                'description' => ucwords("{$dt->monthName}"),
                'data'        => collect([]),
                'total'       => (object) [
                    "km_traveled" => 0,
                    "amount"      => 0,
                ]
            ]);
       }

        $this->VehicleRepositoryEloquent->pushCriteria(VehicleReportsCriteria::class);

        switch ($request->format) {
            case 'excel':
                $vehicles = $this->VehicleRepositoryEloquent->get();
                $months   = $this->mapKmTraveled($months, $vehicles);
                $totals   = $this->mapTotals($months, ["km_traveled", "amount"]);

                return Excel::download(
                    new ViewExport ([
                        'data' => [
                            "rows"    => $vehicles,
                            "columns" => $months,
                            "totals"  => $totals,
                            "year"    => $request->year,
                        ],
                        'view' => 'reports.excel....'
                    ]),
                    '....xlsx'
                );
            break;
                
            case 'pdf':
                $vehicles = $this->VehicleRepositoryEloquent->get();
                $months   = $this->mapKmTraveled($months, $vehicles);
                $totals   = $this->mapTotals($months, ["km_traveled", "amount"]);
                $params   = [
                    "rows"    => $vehicles,
                    "columns" => $months,
                    "totals"  => $totals,
                    "year"    => $request->year,
                ];

                if($request->executive){
                    return $params;
                }

                return PDF::loadView('reports/pdf/reports_km_month', $params)
                           ->stream('reports_km_month.pdf');

            break;

            default:    
                $vehicles = $this->VehicleRepositoryEloquent->paginate();
                $months   = $this->mapKmTraveled($months, $vehicles->items());
                $totals   = $this->mapTotals($months, ["km_traveled", "amount"]);

                return [
                   "rows"    => $vehicles,
                   "columns" => $months,
                   "totals"  => $totals
                ];

            break;
        }

    }

    /**
     * Mapea los kilometrajes por mes
     * 
     * @param $months Meses
     * @param $vehicles Colección de vehiculos
     */
    private function mapKmTraveled($months, $vehicles)
    {   
        foreach ($months as $month) {
            foreach ($vehicles as $vehicle) {
                
                $totalTraveled = $this->totalKmTraveled($vehicle, $month);

                $month["data"]->push([
                    'vehicle_id'    => $vehicle->id,
                    'km_traveled'   => $totalTraveled["km_traveled"],
                    'amount'        => $totalTraveled["amount"],
                ]);
            }

            $month["total"]->km_traveled = $month["data"]->reduce(function($a, $b){
                return $a + $b["km_traveled"];
            }, 0);

            $month["total"]->amount = $month["data"]->reduce(function($a, $b){
                return $a + $b["amount"];
            }, 0);
        }

        return $months;
    }

    /**
     * Calcula el total KM por mes
     * 
     * @param $vehicle Unidad
     * @param $month[since] Dia desde del mes
     * @param $month[until] Dia hasta del mes
     */
    private function totalKmTraveled($vehicle, $month){
        $data           = [];
        $whereAmount    = "vehicle_id = $vehicle->id";
        $whereAmountBt  = "date BETWEEN '{$month['since']}' AND '{$month['until']}'";


        $data["km_traveled"] = DB::table("vehicles_km_tracker")
                                    ->select(DB::raw("sum((km_current - km_previous)) as km_traveled"))
                                    ->where("vehicle_id", $vehicle->id)
                                    ->whereBetween("updated_at", [$month["since"], $month["until"]])
                                    ->where("deleted_at", null)
                                    ->first()
                                    ->km_traveled ?? 0;
        $data["amount"]      = DB::select("
                                  SELECT *, sum(amount) as amount
                                  FROM 
                                  (
                                    SELECT id, 'services_vehicles' as entity, vehicle_id, amount, event_date as date, deleted_at FROM `services_vehicles` 
                                    WHERE deleted_at is null
                                    UNION

                                    SELECT id, 'fuels' as entity, vehicle_id, amount, created_at as date, deleted_at  FROM `fuels` 
                                    WHERE deleted_at is null
                                    UNION

                                    SELECT  id, 'payments' as entity, vehicle_id, amount, created_at as date, deleted_at FROM `payments`
                                    WHERE deleted_at is null
                                  ) as union_table
                                  WHERE $whereAmount AND $whereAmountBt
                               ")[0]->amount ?? 0;
                 
        return $data;
    }

    
    /**
     * Reporte de combustible por mes
     */
    public function fuelsMonths(Request $request)
    {
        $request->validate([
            "year"       => "required",
            "format"     => "nullable",
            "vehicle_id" => "nullable" //example: 1,2,34
        ]);

        $period = \Carbon\CarbonPeriod::create("{$request->year}-01-01", '1 month', "{$request->year}-12-01");
        $months = collect([]);

        foreach ($period as $dt) {
            $months->push([
                'month_year'  => $dt->format("Y-m"),
                'since'       => $dt->format("Y-m-01"),
                'until'       => $dt->format("Y-m-{$dt->endOfMonth()->format('d')}"),
                'description' => ucwords("{$dt->monthName}"),
                'data'        => collect([]),
                'total'       => (object) [
                    "total_loaded" => 0,
                    "amount" => 0,
                ]
            ]);
       }

        $this->VehicleRepositoryEloquent->pushCriteria(VehicleReportsCriteria::class);

        switch ($request->format) {
            case 'excel':
                $vehicles = $this->VehicleRepositoryEloquent->has("fuels")->get();
                $months   = $this->mapMonths($months, $vehicles);
                $totals   = $this->mapTotals($months, ["total_loaded", "amount"]);

                return Excel::download(
                    new ViewExport ([
                        'data' => [
                            "rows"    => $vehicles,
                            "columns" => $months,
                            "totals"  => $totals,
                            "year"    => $request->year,
                        ],
                        'view' => 'reports.excel....'
                    ]),
                    '....xlsx'
                );
            break;
                
            case 'pdf':
                $vehicles = $this->VehicleRepositoryEloquent->has("fuels")->get();
                $months   = $this->mapMonths($months, $vehicles);
                $totals   = $this->mapTotals($months, ["total_loaded", "amount"]);
                $params   = [
                    "rows"    => $vehicles,
                    "columns" => $months,
                    "totals"  => $totals,
                    "year"    => $request->year,
                ];

                if($request->executive){
                    return $params;
                }

                return PDF::loadView('reports/pdf/reports_fuel_month', $params)
                           ->stream('reports_fuel_month.pdf');

            break;

            default:    
                $vehicles = $this->VehicleRepositoryEloquent->has("fuels")->paginate();
                $months   = $this->mapMonths($months, $vehicles->items());
                $totals   = $this->mapTotals($months, ["total_loaded", "amount"]);

                return [
                   "rows"    => $vehicles,
                   "columns" => $months,
                   "totals"  => $totals
                ];

            break;
        }
    }

    /**
     * Mapea los valores de los vehiclos para los meses
     * 
     * @param $months Meses
     * @param $vehicles Colección de vehiculos
     */
    private function mapMonths($months, $vehicles)
    {   
        foreach ($months as $month) {
            foreach ($vehicles as $vehicle) {
                
                $fuel_loaded = $this->totalFuelVehicle($vehicle, $month);

                $month["data"]->push([
                    'vehicle_id'    => $vehicle->id,
                    'total_loaded'  => $fuel_loaded["total_loaded"],
                    'amount'        => $fuel_loaded["amount"],
                ]);
            }

            $month["total"]->total_loaded = $month["data"]->reduce(function($a, $b){
                return $a + $b["total_loaded"];
            }, 0);

            $month["total"]->amount = $month["data"]->reduce(function($a, $b){
                return $a + $b["amount"];
            }, 0);

        }

        return $months;
    }

    /**
     * Calcula el total de combustible de una unidad
     * 
     * @param $vehicle Unidad
     * @param $month[since] Dia desde del mes
     * @param $month[until] Dia hasta del mes
     */
    private function totalFuelVehicle($vehicle, $month){
        $data = [];

        $data["total_loaded"] = $this->FuelRepositoryEloquent
                                    ->select(DB::raw("SUM(lts_loaded) as lts_loaded"))
                                    ->where("vehicle_id", $vehicle->id)
                                    ->whereBetween("created_at", [$month["since"], $month["until"]])
                                    ->first()
                                    ->lts_loaded ?? 0;

        $data["amount"] = $this->FuelRepositoryEloquent
                                    ->select(DB::raw("SUM(amount) as amount"))
                                    ->where("vehicle_id", $vehicle->id)
                                    ->whereBetween("created_at", [$month["since"], $month["until"]])
                                    ->first()
                                    ->amount ?? 0;

        return $data;
    }

    /**
     * Mapea los totales de los meses
     * 
     * @param $months Meses
     * @param $keys Lista de keys que se van a mapear a los totales
     */
    private function mapTotals($months, $keys){
        $data = [];

        if(in_array("total_loaded", $keys)){
            $data["total_loaded"] = $months->reduce(function($a, $b){
                return $a + $b["total"]->total_loaded;
            }, 0);
        }

        if(in_array("km_traveled", $keys)){
            $data["km_traveled"] = $months->reduce(function($a, $b){
                return $a + $b["total"]->km_traveled;
            }, 0);
        }

        if(in_array("amount", $keys)){
            $data["amount"] = $months->reduce(function($a, $b){
                return $a + $b["total"]->amount;
            }, 0);
        }

        if(in_array("services", $keys)){
            $data["services"] = $months->reduce(function($a, $b){
                return $a + $b["total"]->services;
            }, 0);
        }

        return $data;
    }



}
