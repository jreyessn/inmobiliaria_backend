<?php

namespace App\Observers\Credit;

class CreditDestroy
{
    public function deleted($store)
    {
        $store->payments()->delete();
        $store->cuotes()->delete();
    }
}
