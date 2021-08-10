<?php

namespace App\Observers;

use App\Models\Audit;
use Illuminate\Support\Facades\DB;

class AuditObserver
{
    
    public function created($store)
    {
        Audit::create(
            [
                "action"     => "CREAR",
                "user_id"    => request()->user()->id ?? null,
                "model_type" => get_class($store),
                "model_id"   => $store->id,
                "query"    => last_query()
            ]
        );
    }

    public function updated($store)
    {
        Audit::create(
            [
                "action"     => "ACTUALIZAR",
                "user_id"    => request()->user()->id ?? null,
                "model_type" => get_class($store),
                "model_id"   => $store->id,
                "query"    => last_query()
            ]
        );
    }

    public function deleted($store)
    {
        Audit::create(
            [
                "action"     => "ELIMINAR",
                "user_id"    => request()->user()->id ?? null,
                "model_type" => get_class($store),
                "model_id"   => $store->id,
                "query"    => last_query()
            ]
        );
    }
}
