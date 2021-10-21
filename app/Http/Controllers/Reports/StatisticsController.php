<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Repositories\Coupons\CouponsMovementsRepositoryEloquent;
use App\Repositories\Customer\CustomerRepositoryEloquent;
use App\Repositories\Users\UserRepositoryEloquent;
use Carbon\Carbon;
use Illuminate\Http\Request;
use stdClass;

class StatisticsController extends Controller
{

    private $couponsMovementRepositoryEloquent;

    private $customerRepositoryEloquent;

    private $userRepositoryEloquent;

    public function __construct(
        CouponsMovementsRepositoryEloquent $couponsMovementRepositoryEloquent,
        CustomerRepositoryEloquent $customerRepositoryEloquent,
        UserRepositoryEloquent $userRepositoryEloquent
    )
    {
        $this->couponsMovementRepositoryEloquent = $couponsMovementRepositoryEloquent;    
        $this->customerRepositoryEloquent        = $customerRepositoryEloquent;    
        $this->userRepositoryEloquent            = $userRepositoryEloquent;    

    }

    public function graphics(Request $request)
    {
        $request->validate([
            "since"  => "required|date",
            "until"  => "required|date",
        ]);

        $data["deliveriesMonthly"]      = $this->deliveriesMonthly($request);
        $data["customersMorePurchases"] = $this->customersMorePurchases($request);
        $data["usersMoreDeliveries"]    = $this->usersMoreDeliveries($request);
        $data["salesMonthly"]           = $this->salesMonthly($request);
        
        return $data;
    }

    /**
     * Entregas mensuales
     */
    private function deliveriesMonthly($request)
    {

        $months = period_months($request->since, $request->until);
        
        foreach ($months as $key => $month) {
            
            $quantity = $this->couponsMovementRepositoryEloquent
                                ->where("type_movement", getMovement(3))
                                ->whereBetween("created_at", [ $month["first_day"], $month["end_day"] ])
                                ->get();

           $months[$key]['data'] = $quantity->count();

       }

       return $months;

    }

    /**
     * Ventas mensuales
     */
    private function salesMonthly($request)
    {

        $months = period_months($request->since, $request->until);
        
        foreach ($months as $key => $month) {
            
            $quantity = $this->couponsMovementRepositoryEloquent
                                ->where("type_movement", getMovement(1))
                                ->whereBetween("created_at", [ $month["first_day"], $month["end_day"] ])
                                ->get();

           $months[$key]['data'] = $quantity->count();

       }

       return $months;

    }

    /**
     * Clientes que más cupones han utilizado
     */
    private function customersMorePurchases($request)
    {
        $customers = $this->customerRepositoryEloquent
                            ->select("id", "tradename", "business_name")
                            ->get()
                            ->map(function($item) use ($request){

                                $item->coupons_purchases = $item->movements()
                                                                ->whereBetween("created_at", [ $request["since"], $request["until"] ])
                                                                ->where("type_movement", getMovement(1))
                                                                ->sum("quantity");

                                return $item;
                            });

        
        return $customers;
    }

    /**
     * Usuarios con más entregas
     */
    private function usersMoreDeliveries($request)
    {
        $customers = $this->userRepositoryEloquent
                            ->select("id", "username", "name")
                            ->whereHas("roles", function($q){
                                $q->where("name", "Repartidor");
                            })
                            ->get()
                            ->map(function($item) use ($request){

                                $item->quantity_coupons = $item->movements_coupons()
                                                                ->whereBetween("coupons_movements.created_at", [ $request["since"], $request["until"] ])
                                                                ->where("type_movement", getMovement(3))
                                                                ->sum("quantity");

                                return $item;
                            });

        
        return $customers;
    }

}
