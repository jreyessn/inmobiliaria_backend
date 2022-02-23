<?php

namespace App\Http\Controllers;

use App\Models\Services\CategoriesService;
use App\Models\Services\Service;
use App\Models\Services\TypesService;
use App\Models\TypeTicket;
use Illuminate\Http\Request;
use App\Models\System\System;
use App\Models\Ticket\Ticket;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    
    function __invoke(Request $request)
    {
     
        $data = [];

        if($request->has("type_maintenance")){
            $data["type_maintenance"] = $this->type_maintenance();
        }

        if($request->has("type_services")){
            $data["type_services"] = $this->type_services();
        }

        if($request->has("information_general")){
            $data["information_general"] = $this->information_general();
        }

        if($request->has("services")){
            $data["services"] = $this->services();
        }

        return $data;
    }

    /**
     * Datos para tipos de mantenimientos
     */
    function type_maintenance(){
        $chart = [];
        $categories = CategoriesService::get();

        $chart["labels"] = $categories->map(function($item){
            return $item->name;
        });

        $chart["colors"] = [
            '#7cdf46',
            '#1cbcd8'
        ];

        $chart["series"] = $categories->map(function($item){
            return [
                "name" => $item->name,
                "data" => [ $item->services()->count() ]
            ];
        });

        return $chart;
    }

    /**
     * Datos para tipos de servicios
     */
    function type_services(){
        $chart = [];
        $data = TypesService::get();

        $chart["labels"] = $data->map(function($item){
            return $item->name;
        });

        $chart["colors"] = [
            '#4d87ca',
            '#41b788',
            '#5b0d71',
            '#ee2798',
        ];

        $chart["series"] = $data->map(function($item){
            return $item->services()->count();
        });

        return $chart;
    }

    /**
     * Información general
     */
    function information_general(){
        $chart = [];
        
        $chart["completed"] = DB::table("services")
                                 ->select(DB::raw("count(id) as quantity"))
                                 ->where("status", 1)
                                 ->where("deleted_at", null)
                                 ->first()->quantity;

        $chart["pending"]   = DB::table("services")
                                  ->select(DB::raw("count(id) as quantity"))
                                  ->where("status", 0)
                                  ->where(DB::raw("date(event_date)"), ">=" , now()->format("Y-m-d"))
                                  ->where("deleted_at", null)
                                  ->first()->quantity;

        $chart["expired"]   = DB::table("services")
                                  ->select(DB::raw("count(id) as quantity"))
                                  ->where("status", 0)
                                  ->where(DB::raw("date(event_date)"), "<" , now()->format("Y-m-d"))
                                  ->where("deleted_at", null)
                                  ->first()->quantity;

        return $chart;
    }

    /**
     * Ultimos servicios
     */
    function services(){
        $chart = [];
        
        $chart["last_pending_without_user"] = Service::where("status", 1)->limit(7)
                                                    ->where("user_assigned_id", null)
                                                    ->where(DB::raw("date(event_date)"), ">=" , now()->format("Y-m-d"))
                                                    ->orderBy("event_date", "asc")
                                                    ->with(["equipment"])
                                                    ->get();

        $chart["last_pending_with_user"]   = Service::where("status", 0)->limit(7)
                                                    ->where("user_assigned_id", "!=",null)
                                                    ->where(DB::raw("date(event_date)"), ">=" , now()->format("Y-m-d"))
                                                    ->orderBy("event_date", "asc")
                                                    ->with(["equipment"])
                                                    ->get();

        $chart["last_expired"]   = Service::where("status", 0)->limit(7)
                                            ->where(DB::raw("date(event_date)"), "<" , now()->format("Y-m-d"))
                                            ->orderBy("event_date", "desc")
                                            ->with(["equipment"])
                                            ->get();

        return $chart;
    }
   
}
