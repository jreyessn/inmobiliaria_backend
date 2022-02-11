<?php

namespace App\Providers;

use App\Models\Configuration;
use App\Models\Vehicle\Fuel;
use App\Models\Vehicle\Payment;
use App\Models\Vehicle\ServiceVehicle;
use App\Observers\KmTrackerObserver;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\UrlGenerator;

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
        Schema::defaultStringLength(191);

        if (Schema::hasTable('configurations')) {
            $all = Configuration::all();

            foreach ($all as $setting) {

                Config::set('configuration.'.$setting->key, $setting->value);
            }
        }

        if (env('APP_ENV') === 'production') {
            $url->forceScheme('https');
        }

        // observers
        ServiceVehicle::observe(KmTrackerObserver::class);
        Fuel::observe(KmTrackerObserver::class);
        Payment::observe(KmTrackerObserver::class);


    }
}
