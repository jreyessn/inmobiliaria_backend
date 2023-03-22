<?php

namespace App\Observers;

use App\Models\BranchOffices\ModelHasBranchOffice;

class BranchOfficeObserver
{
    public function created($store)
    {
        $office_current_id = request()->get("branch_office_current");

        if($office_current_id){
            ModelHasBranchOffice::create(
                [
                    "branch_office_id" => $office_current_id,
                    "model_type"       => get_class($store),
                    "model_id"         => $store->id,
                ]
            );
        }
    }
}
