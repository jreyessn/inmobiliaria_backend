<?php

namespace App\Models\Customer;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerSubscription extends Model
{
    use HasFactory;

    protected $fillable = [
        "customer_id",
        "start_date",
        "every_day",
        "last_pay_date",
        "next_pay_date",
        "quantity_coupons",
    ];

    protected static function boot()
    {
        parent::boot();

        /**
         * Se actualiza la fecha del proximo pago
         */
        static::created(function($store){
            $start_date = new Carbon($store->start_date);
            $next_pay = $start_date->addDays($store->every_day);
            
            $store->next_pay_date = $next_pay;
            $store->save();
        });

        static::updated(function($store){
            $last_pay_date = $store->last_pay_date? new Carbon($store->last_pay_date) : new Carbon($store->start_date);
            
            $store->next_pay_date = $last_pay_date->addDays($store->every_day);
            $store->unsetEventDispatcher();
            $store->save();
        });


    }
}
