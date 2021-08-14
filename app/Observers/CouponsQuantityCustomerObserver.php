<?php

namespace App\Observers;

use App\Models\Coupons\CouponsMovements;

class CouponsQuantityCustomerObserver
{
    
    public function created(CouponsMovements $store)
    {
        
        $customer = $store->customer;

        if($store->type_movement == "Compra"){
            $customer->coupons = (int) $customer->coupons + (int) $store->quantity; 
            $customer->save();
        }
        
        if($store->type_movement == "Venta" || $store->type_movement == "Devolución"){
            $customer->coupons = (int) $customer->coupons - (int) $store->quantity; 
            $customer->save();
        }

    }

    public function deleted(CouponsMovements $store)
    {
        
        $customer = $store->customer;
        
        if($store->type_movement == "Compra"){
            $customer->coupons = (int) $customer->coupons - (int) $store->quantity; 
            $customer->save();
        }
        
        if($store->type_movement == "Venta" || $store->type_movement == "Devolución"){
            $customer->coupons = (int) $customer->coupons + (int) $store->quantity; 
            $customer->save();
        }

    }

}
