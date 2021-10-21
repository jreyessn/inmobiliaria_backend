<?php

namespace App\Observers;

use App\Models\Coupons\CouponsMovements;

class CouponsQuantityCustomerObserver
{
    
    public function created(CouponsMovements $store)
    {
        
        $customer = $store->customer;

        if(
            $store->type_movement == getMovement(1)  || 
            $store->type_movement == getMovement(2) || 
            ($store->type_movement == getMovement(4) && $store->io == 1)
        ){
            $customer->coupons = (int) $customer->coupons + (int) $store->quantity; 
            $customer->save();
        }
        
        if(
            $store->type_movement == getMovement(3) ||
            ($store->type_movement == getMovement(4) && $store->io == 2)
        ){
            $customer->coupons = (int) $customer->coupons - (int) $store->quantity; 
            $customer->save();
        }

    }

    public function deleted(CouponsMovements $store)
    {
        
        $customer = $store->customer;
        
        if($store->type_movement == getMovement(1) || $store->type_movement == getMovement(2)){
            $customer->coupons = (int) $customer->coupons - (int) $store->quantity; 
            $customer->save();
        }
        
        if($store->type_movement == getMovement(3) || $store->type_movement == getMovement(4)){
            $customer->coupons = (int) $customer->coupons + (int) $store->quantity; 
            $customer->save();
        }

    }

}
