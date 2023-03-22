<?php

namespace App\Providers;

use App\Models\Customer\Customer;
use App\Models\Furniture\Furniture;
use App\Models\Sale\Credit;
use App\Models\Sale\CreditCuote;
use App\Models\Sale\CreditPayment;
use App\Observers\BranchOfficeObserver;
use Illuminate\Support\ServiceProvider;

class BranchOfficeProvider extends ServiceProvider
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
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Furniture::observe(BranchOfficeObserver::class);
        Credit::observe(BranchOfficeObserver::class);
        CreditCuote::observe(BranchOfficeObserver::class);
        CreditPayment::observe(BranchOfficeObserver::class);
        Customer::observe(BranchOfficeObserver::class);
    }
}
