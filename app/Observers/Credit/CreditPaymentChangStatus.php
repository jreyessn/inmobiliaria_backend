<?php

namespace App\Observers\Credit;

class CreditPaymentChangStatus
{
    public function created($store)
    {
        $credit_pending = $store->credit_cuote->credit->amount_pending ?? 0;

        if($credit_pending == 0){
            $store->credit_cuote->credit->update(["status" => 1]);
        }
    }
}
