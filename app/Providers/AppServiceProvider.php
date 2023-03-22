<?php

namespace App\Providers;

use App\Models\Configuration;
use App\Models\Country\City;
use App\Models\Country\Country;
use App\Models\Sale\Credit;
use App\Models\Sale\CreditPayment;
use App\Models\Vehicle\Fuel;
use App\Models\Vehicle\Payment;
use App\Models\Vehicle\ServiceVehicle;
use App\Observers\Credit\CreditDestroy;
use App\Observers\Credit\CreditPaymentChangStatus;
use App\Observers\KmTrackerObserver;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\UrlGenerator;

use Laravel\Passport\Console\ClientCommand;
use Laravel\Passport\Console\InstallCommand;
use Laravel\Passport\Console\KeysCommand;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(
        UrlGenerator $url
    )
    {
        // configurations
        $this->setConfigDatabase();
        Schema::defaultStringLength(191);
        Passport::routes();

        /*ADD THIS LINES*/
        $this->commands([
            InstallCommand::class,
            ClientCommand::class,
            KeysCommand::class,
        ]);

        // Observers
        CreditPayment::observe(CreditPaymentChangStatus::class);
        Credit::observe(CreditDestroy::class);
    }

    private function setConfigDatabase(){
        if (Schema::hasTable('configuration')) {
            $all = Configuration::all();

            foreach ($all as $setting) {

                if($setting->key == "country_id" && Schema::hasTable("countries")){
                    $country_name = Country::find($setting->value)->name ?? '';
                    Config::set('app.country_name', $country_name);
                }

                if($setting->key == "city_id" && Schema::hasTable("cities")){
                    $city_name = City::find($setting->value)->name ?? '';
                    Config::set('app.city_name', $city_name);
                }

                Config::set('app.'.$setting->key, $setting->value);
            }
        }
    }
}
