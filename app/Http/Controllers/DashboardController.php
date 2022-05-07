<?php

namespace App\Http\Controllers;

use App\Models\Customer\Customer;
use App\Models\Furniture\Furniture;
use App\Models\Movements\MovementDetail;
use App\Models\Products\Stock;
use App\Models\Sale\Credit;
use App\Models\Sale\CreditCuote;
use App\Models\Services\CategoriesService;
use App\Models\Services\Service;
use App\Models\Services\TypesService;
use App\Models\TypeTicket;
use Illuminate\Http\Request;
use App\Models\System\System;
use App\Models\Ticket\Ticket;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{

    private $colors = [
        "#A3E635",
        "#22D3EE",
        "#1E40AF",
        "#0E7490",
        "#991B1B",
        "#065F46",
        "#155E75",
    ];
    
    function __invoke(Request $request)
    {
     
        $data = [];

        if($request->has("customers_quantity")){
            $data["customers_quantity"] = $this->customers_quantity();
        }
        if($request->has("furnitures_quantity_complete")){
            $data["furnitures_quantity_complete"] = $this->furnitures_quantity_complete();
        }
        if($request->has("furnitures_quantity_pending")){
            $data["furnitures_quantity_pending"] = $this->furnitures_quantity_pending();
        }
        if($request->has("cuotes_paid")){
            $data["cuotes_paid"] = $this->cuotes_paid();
        }

        if($request->has("overview")){
            $data["overview"] = $this->overview();
        }
        if($request->has("next_cuotes")){
            $data["next_cuotes"] = $this->next_cuotes();
        }
        if($request->has("expired_cuotes")){
            $data["expired_cuotes"] = $this->expired_cuotes();
        }
        if($request->has("customer_debs")){
            $data["customer_debs"] = $this->customer_debs();
        }

        return $data;
    }

    /**
     * Cantidad de clientes
     */
    function customers_quantity(){
        $since = request()->get("since");
        $until = request()->get("until");
        $chart = [];
        $chart["quantity"] = Customer::count();

        return $chart;
    }

    /**
     * Cantidad de inmuebles pagos
     */
    function furnitures_quantity_complete(){
        $since = request()->get("since");
        $until = request()->get("until");
        $chart = [];
        $chart["quantity"] = Furniture::whereHas("credit", function($query){
            $query->where("status", 1);
        })->count();

        return $chart;
    }

    /**
     * Cantidad de inmuebles pendientes de pago
     */
    function furnitures_quantity_pending(){
        $since = request()->get("since");
        $until = request()->get("until");
        $chart["quantity"] = Furniture::whereHas("credit", function($query){
            $query->where("status", 0);
        })->count();

        return $chart;
    }

    /**
     * Cuotas pagas del mes
     */
    function cuotes_paid(){
        $since  = request()->get("since");
        $until  = request()->get("until");
        $month  = request()->get("month", now()->format("m"));

        $dt            = Carbon::create(null, $month);
        $firstDayMonth = $dt->format("Y-m-01 00:00:00");
        $lastDayMonth  = $dt->format("Y-m-{$dt->endOfMonth()->format('d')} 23:59:59");
        
        $chart  = [];
        $cuotes = CreditCuote::whereBetween("expiration_at", [$firstDayMonth, $lastDayMonth])->get();

        $chart["paids"]    = $cuotes->where("status", 1)->count();
        $chart["quantity"] = $cuotes->count();

        return $chart;
    }

    /**
     * Proximas cuotas
     */
    function next_cuotes(){
        $since  = request()->get("since");
        $until  = request()->get("until");
        $currency_id = request()->get("currency_id", null);
        $chart  = [];

        $chart["cuotes"] = DB::table("credit_cuotes")
                              ->selectRaw("
                                credit_cuotes.id,
                                credit_cuotes.number_letter,
                                (credit_cuotes.total - SUM(credit_payments.amount)) AS amount_pending,
                                credit_cuotes.total,
                                credit_cuotes.expiration_at,
                                furniture.name as furniture_name,
                                customers.name as customer_name
                              ")
                              ->join("credit_payments", "credit_payments.credit_cuote_id", "=", "credit_cuotes.id", "left")
                              ->join("credits", "credits.id", "=", "credit_cuotes.credit_id", "left")
                              ->join("furniture", "furniture.id", "=", "credits.furniture_id", "left")
                              ->join("customers", "customers.id", "=", "furniture.customer_id", "left")
                              ->when($currency_id, function($query) use ($currency_id){
                                    $query->where(function($query) use ($currency_id){
                                        $query->where("credit_payments.currency_id", $currency_id);
                                        $query->orWhere("furniture.currency_id", $currency_id);
                                    });
                               })
                              ->whereNull("credits.deleted_at")
                              ->where("credit_cuotes.expiration_at", ">=", now())
                              ->groupBy("credit_cuotes.id")
                              ->havingRaw("amount_pending > 0 or amount_pending is null")
                              ->orderBy("expiration_at", "asc")
                              ->limit(8)
                              ->get()
                              ->map(function($data){
                                $data->credit_cuote = CreditCuote::find($data->id);

                                return $data;
                              });

        return $chart;
    }

    /**
     * Cuotas expiradas
     */
    function expired_cuotes(){
        $since  = request()->get("since");
        $until  = request()->get("until");
        $currency_id = request()->get("currency_id", null);
        $chart  = [];

        $chart["cuotes"] = DB::table("credit_cuotes")
                              ->selectRaw("
                                credit_cuotes.id,
                                credit_cuotes.number_letter,
                                (credit_cuotes.total - SUM(credit_payments.amount)) AS amount_pending,
                                credit_cuotes.total,
                                credit_cuotes.expiration_at,
                                furniture.name as furniture_name,
                                customers.name as customer_name
                              ")
                              ->join("credit_payments", "credit_payments.credit_cuote_id", "=", "credit_cuotes.id", "left")
                              ->join("credits", "credits.id", "=", "credit_cuotes.credit_id", "left")
                              ->join("furniture", "furniture.id", "=", "credits.furniture_id", "left")
                              ->join("customers", "customers.id", "=", "furniture.customer_id", "left")
                              ->when($currency_id, function($query) use ($currency_id){
                                    $query->where(function($query) use ($currency_id){
                                        $query->where("credit_payments.currency_id", $currency_id);
                                        $query->orWhere("furniture.currency_id", $currency_id);
                                    });
                                })
                              ->whereNull("credits.deleted_at")
                              ->where("credit_cuotes.expiration_at", "<", now())
                              ->groupBy("credit_cuotes.id")
                              ->havingRaw("amount_pending > 0 or amount_pending is null")
                              ->orderBy("expiration_at", "asc")
                              ->limit(8)
                              ->get()
                              ->map(function($data){
                                    $data->credit_cuote = CreditCuote::find($data->id);

                                    return $data;
                              });
        return $chart;
    }

    /**
     * Clientes con más deuda
     */
    function customer_debs(){
        $since  = request()->get("since");
        $until  = request()->get("until");
        $currency_id = request()->get("currency_id", null);
        $chart  = [];

        $customers = DB::table("customers")
                            ->selectRaw("customers.id, (sum(DISTINCT credits.total) - sum(credit_payments.amount)) as amount_pending")
                            ->join("furniture", "furniture.customer_id", "=", "customers.id", "left")
                            ->join("credits", "credits.furniture_id", "=", "furniture.id", "left")
                            ->join("credit_cuotes", "credit_cuotes.credit_id", "=", "credits.id", "left")
                            ->join("credit_payments", "credit_payments.credit_cuote_id", "=", "credit_cuotes.id", "left")
                            ->whereNull("furniture.deleted_at")
                            ->whereNull("credits.deleted_at")
                            ->whereNull("credit_cuotes.deleted_at")
                            ->whereNull("credit_payments.deleted_at")
                            ->when($currency_id, function($query) use ($currency_id){
                                $query->where(function($query) use ($currency_id){
                                    $query->where("credit_payments.currency_id", $currency_id);
                                    $query->orWhere("furniture.currency_id", $currency_id);
                                });
                            })
                            ->groupBy("customers.id")
                            ->orderBy("amount_pending", "desc")
                            ->havingRaw("amount_pending is not null")
                            ->get()
                            ->map(function($data){
                                $data->customer = Customer::find($data->id);

                                return $data;
                            });

        $chart["labels"] = $customers->map(function($item){
            return $item->customer->name;
        });

        $chart["colors"] = [ '#0284C7' ];
        $chart["fill"] = [
            "colors" => ["#0284C7"]
        ];

        $chart["series"] = [
            [
                "name" => "Saldo",
                "data" => $customers->map(function($item){
                    return $item->amount_pending;
                })
            ]
        ]; 

        return $chart;
    }

    /**
     * Visión general por meses 
     */
    function overview(){
        $year = request()->get("year", date("Y"));
        $currency_id = request()->get("currency_id", null);
        $period = \Carbon\CarbonPeriod::create("{$year}-01-01", '1 month', "{$year}-12-01");
        $months = collect([]);

        foreach ($period as $dt) {
            $months->push((Object) [
                'year'        => $dt->format("Y"),
                'first_day'   => $dt->format("Y-m-01"),
                'end_day'     => $dt->format("Y-m-{$dt->endOfMonth()->format('d')}"),
                'description' => ucwords("{$dt->monthName}")
            ]);
       }

        /* Se consulta por cada mes */
        foreach ($months as $month) {
            $closure = function() use ($month, $currency_id){
                $values = DB::table("credit_cuotes")
                                ->selectRaw("
                                    credit_cuotes.id,
                                    (credit_cuotes.total - SUM(credit_payments.amount)) AS amount_pending,
                                    SUM(credit_payments.amount) as total_paid,
                                    credit_payments.created_at
                                ")
                                ->join("credit_payments", "credit_payments.credit_cuote_id", "=", "credit_cuotes.id", "left")
                                ->whereNull("credit_cuotes.deleted_at")
                                ->whereBetween("credit_payments.created_at", [$month->first_day, $month->end_day])
                                ->when($currency_id, function($query) use ($currency_id){
                                    $query->where("credit_payments.currency_id", $currency_id);
                                })
                                ->groupBy("credit_cuotes.id")
                                ->havingRaw("total_paid is not null")
                                ->orderBy("credit_payments.created_at", "asc")
                                ->get();

                
                return [
                    "total_paid" => $values->reduce(function($a, $b) {
                        return $a + $b->total_paid;
                    }, 0)
                ];
            };

            $month->data = $closure();
        }

        $chart["colors"] = ['var(--fuse-primary)', '#0E7490'];
        $chart["fill"] = [
            "colors" => ['var(--fuse-primary-200)', '#67E8F9']
        ];

        $chart["series"] = [
            [
                "name" => "Ingresos por Cuotas",
                "data" => $months->map(function($month){
                    return [
                        "x" => $month->description,
                        "y" => $month->data["total_paid"]
                    ];
                })
            ],
        ];

        return $chart;
    }

   
}
