<?php

namespace App\Providers;

use App\Models\Coupons\CouponsMovements;
use App\Models\Coupons\CouponsRequest;
use App\Models\Customer\Customer;
use App\Models\User;
use App\Models\Visit\Visit;
use App\Observers\AuditObserver;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

/**
 * Auditoria para el Sistema
 */
class AuditServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Modelos que van a tener auditoria
     *
     * @return void
     */
    public function boot()
    {
        DB::enableQueryLog();

        User::observe(AuditObserver::class);
    }
}
