<?php

namespace App\Providers;

use App\Models\Configuration;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;

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
    public function boot()
    {
        Schema::defaultStringLength(191);

        if (Schema::hasTable('configurations')) {
            $all = Configuration::all();

            foreach ($all as $setting) {

                Config::set('configuration.'.$setting->key, $setting->value);
            }
        }
    }
}
