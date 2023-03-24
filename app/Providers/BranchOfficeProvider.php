<?php

namespace App\Providers;

use App\Criteria\BranchOffice\BranchOfficeActiveCriteria;
use App\Models\Customer\Customer;
use App\Models\Furniture\Furniture;
use App\Models\Sale\Credit;
use App\Models\Sale\CreditCuote;
use App\Models\Sale\CreditPayment;
use App\Observers\BranchOfficeObserver;
use App\Repositories\Customer\CustomerRepositoryEloquent;
use App\Repositories\Furniture\FurnitureRepositoryEloquent;
use App\Repositories\Sale\CreditCuoteRepositoryEloquent;
use App\Repositories\Sale\CreditPaymentRepositoryEloquent;
use App\Repositories\Sale\CreditRepositoryEloquent;
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
        $this->app->bind(FurnitureRepositoryEloquent::class, function ($app) {
            $FurnitureRepositoryEloquent = new FurnitureRepositoryEloquent($app);
            $FurnitureRepositoryEloquent->pushCriteria(BranchOfficeActiveCriteria::class);
            return $FurnitureRepositoryEloquent;
        });        
        
        $this->app->bind(CustomerRepositoryEloquent::class, function ($app) {
            $CustomerRepositoryEloquent = new CustomerRepositoryEloquent($app);
            $CustomerRepositoryEloquent->pushCriteria(BranchOfficeActiveCriteria::class);
            return $CustomerRepositoryEloquent;
        });     

        $this->app->bind(CreditRepositoryEloquent::class, function ($app) {
            $CreditRepositoryEloquent = new CreditRepositoryEloquent($app);
            $CreditRepositoryEloquent->pushCriteria(BranchOfficeActiveCriteria::class);
            return $CreditRepositoryEloquent;
        });  

        $this->app->bind(CreditCuoteRepositoryEloquent::class, function ($app) {
            $CreditCuoteRepositoryEloquent = new CreditCuoteRepositoryEloquent($app);
            $CreditCuoteRepositoryEloquent->pushCriteria(BranchOfficeActiveCriteria::class);
            return $CreditCuoteRepositoryEloquent;
        });    

        $this->app->bind(CreditPaymentRepositoryEloquent::class, function ($app) {
            $CreditPaymentRepositoryEloquent = new CreditPaymentRepositoryEloquent($app);
            $CreditPaymentRepositoryEloquent->pushCriteria(BranchOfficeActiveCriteria::class);
            return $CreditPaymentRepositoryEloquent;
        });
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
