<?php

namespace App\Providers;

use App\Models\Furniture\Furniture;
use App\Models\Sale\Sale;
use App\Models\User;
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

        // Historial
        User::observe(AuditObserver::class);
        Sale::observe(AuditObserver::class);
        Furniture::observe(AuditObserver::class);
    }
}
