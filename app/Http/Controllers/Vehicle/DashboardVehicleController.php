<?php

namespace App\Http\Controllers\Vehicle;

use App\Http\Controllers\Controller;
use App\Models\Vehicle\LicensePlate;
use App\Models\Vehicle\PermissionsVehicle;
use App\Models\Vehicle\ServiceVehicle;
use App\Models\Vehicle\TypeServiceVehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardVehicleController extends Controller
{

    function __invoke(Request $request)
    {
     
        $data = [];

        if($request->has("information_general")){
            $data["information_general"] = $this->information_general();
        }

        if($request->has("type_services")){
            $data["type_services"] = $this->type_services();
        }

        if($request->has("maintenances")){
            $data["maintenances"] = $this->maintenances();
        }

        if($request->has("performance_units")){
            $data["performance_units"] = $this->performance_units();
        }

        if($request->has("fuels_units")){
            $data["fuels_units"] = $this->fuels_units();
        }

        if($request->has("licenses_to_expire")){
            $data["licenses_to_expire"] = $this->licenses_to_expire();
        }

        if($request->has("permissions_to_expire")){
            $data["permissions_to_expire"] = $this->permissions_to_expire();
        }

        return $data;
    }

    /**
     * Informacion general de estados de los servicios
     */
    function information_general(){
        $since = request()->get("since");
        $until = request()->get("until");
        $chart = [];
        
        $chart["completed"] = DB::table("services_vehicles")
                                 ->select(DB::raw("count(id) as quantity"))
                                 ->where("status", 1)
                                 ->when($since && $until, function($query) use ($since, $until){
                                    $query->whereBetween("event_date", [$since, $until]);
                                 })
                                 ->where("deleted_at", null)
                                 ->first()->quantity;

        $chart["pending"]   = DB::table("services_vehicles")
                                  ->select(DB::raw("count(id) as quantity"))
                                  ->where("status", 0)
                                  ->when($since && $until, function($query) use ($since, $until){
                                    $query->whereBetween("event_date", [$since, $until]);
                                  })
                                  ->where(DB::raw("date(event_date)"), ">=" , now()->format("Y-m-d"))
                                  ->where("deleted_at", null)
                                  ->first()->quantity;

        $chart["expired"]   = DB::table("services_vehicles")
                                  ->select(DB::raw("count(id) as quantity"))
                                  ->where("status", 0)
                                  ->when($since && $until, function($query) use ($since, $until){
                                        $query->whereBetween("event_date", [$since, $until]);
                                  })
                                  ->where(DB::raw("date(event_date)"), "<" , now()->format("Y-m-d"))
                                  ->where("deleted_at", null)
                                  ->first()->quantity;

        return $chart;
    }

    /**
     * Datos para tipos de servicios
     */
    function type_services(){
        $since = request()->get("since");
        $until = request()->get("until");
        $chart = [];
        $data = TypeServiceVehicle::get();

        $chart["labels"] = $data->map(function($item){
            return $item->name;
        });

        $chart["colors"] = [
            '#4d87ca',
            '#41b788',
            '#5b0d71',
            '#ee2798',
        ];

        $chart["series"] = $data->map(function($item) use ($since, $until){
            return $item->services()
                        ->when($since && $until, function($query) use ($since, $until){
                            $query->whereBetween("event_date", [$since, $until]);
                        })
                        ->count();
        });

        return $chart;
    }

    /**
     * Ultimos servicios pendientes y vencidos
     */
    function maintenances(){
        $since = request()->get("since");
        $until = request()->get("until");
        $chart = [];

        $chart["last_pending"]   = ServiceVehicle::where("status", 0)->limit(7)
                                                    ->where(DB::raw("date(event_date)"), ">=" , now()->format("Y-m-d"))
                                                    ->when($since && $until, function($query) use ($since, $until){
                                                        $query->whereBetween("event_date", [$since, $until]);
                                                    })
                                                    ->orderBy("event_date", "asc")
                                                    ->with(["vehicle", "type_service_vehicle"])
                                                    ->get();

        $chart["last_expired"]   = ServiceVehicle::where("status", 0)->limit(7)
                                            ->where(DB::raw("date(event_date)"), "<" , now()->format("Y-m-d"))
                                            ->when($since && $until, function($query) use ($since, $until){
                                                $query->whereBetween("event_date", [$since, $until]);
                                            })
                                            ->orderBy("event_date", "desc")
                                            ->with(["vehicle", "type_service_vehicle"])
                                            ->get();

        return $chart;
    }

    /**
     * Rendimiento de unidades según combustibles
     */
    function performance_units(){
        $since = request()->get("since");
        $until = request()->get("until");
        $where = "";

        if($since && $until){
            $where = "WHERE fuels.created_at BETWEEN '$since' and '$until'";
        }

        $result = DB::select(DB::raw("
            SELECT 
                vehicle_id,
                vehicles.name,
                SUM((km_current - km_last_fuel)) as km_traveled,
                ROUND(SUM((km_current - km_last_fuel) / lts_loaded) / count(fuel_subs.id), 2) as efficiency 
            FROM 
                (SELECT 
                    id, 
                    vehicle_id, 
                    lts_loaded, 
                    km_current, 
                    IF(
                        (SELECT fuel_subs.km_current FROM fuels as fuel_subs  WHERE fuel_subs.id < fuels.id ORDER BY fuel_subs.id desc LIMIT 1),
                        (SELECT fuel_subs.km_current FROM fuels as fuel_subs  WHERE fuel_subs.id < fuels.id ORDER BY fuel_subs.id desc LIMIT 1),
                        (SELECT km_previous FROM `vehicles_km_tracker` where model_type like '%Fuel%' and model_id = fuels.id LIMIT 1) 
                    ) AS km_last_fuel
                FROM `fuels`
                $where
                ) fuel_subs
            LEFT JOIN vehicles ON vehicles.id = fuel_subs.vehicle_id
            GROUP BY vehicle_id
            ORDER BY efficiency ASC
        "));

        $chart = [];

        $chart["labels"] = collect($result)->map(function($item){
            return $item->name;
        });

        $chart["colors"] = [
            '#4d87ca',
            '#41b788',
            '#5b0d71',
            '#ee2798',
        ];

        $chart["series"] = collect($result)->map(function($item){
            return [
                "name" => $item->name,
                "data" => [ $item->efficiency ]
            ];
        });

        return $chart;
    }

    /**
     * Unidades con más combustibles
     */
    function fuels_units(){
        $since = request()->get("since");
        $until = request()->get("until");
        $where = "";

        if($since && $until){
            $where = "WHERE fuels.created_at BETWEEN '$since' and '$until'";
        }

        $chart = DB::select(DB::raw("
            SELECT 
                vehicle_id,
                vehicles.name,
                SUM(lts_loaded) as lts_loaded,
                count(vehicle_id) as quantity_loaded 
            FROM fuels
            $where
            LEFT JOIN vehicles ON vehicles.id = fuels.vehicle_id
            GROUP BY vehicle_id
            ORDER BY lts_loaded DESC
        "));

        return $chart;
    }

    /**
     * Licencias por expirar
     */
    function licenses_to_expire(){
        $since = request()->get("since");
        $until = request()->get("until");

        $chart = LicensePlate::orderBy("expiration_at", "ASC")->limit(7)
                             ->where(DB::raw("date(expiration_at)"), ">=" , now()->format("Y-m-d"))
                             ->when($since && $until, function($query) use ($since, $until){
                                $query->whereBetween("expiration_at", [$since, $until]);
                             })
                             ->with(["vehicle", "user"])
                             ->get();
        return $chart;
    }

    /**
     * Permisos por expirar
     */
    function permissions_to_expire(){
        $since = request()->get("since");
        $until = request()->get("until");

        $chart = PermissionsVehicle::orderBy("expiration_at", "ASC")->limit(7)
                                ->where(DB::raw("date(expiration_at)"), ">=" , now()->format("Y-m-d"))
                                ->when($since && $until, function($query) use ($since, $until){
                                    $query->whereBetween("expiration_at", [$since, $until]);
                                })
                                ->with(["vehicle"])
                                ->get();
        return $chart;
    }

}
