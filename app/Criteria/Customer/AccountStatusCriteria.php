<?php

namespace App\Criteria\Customer;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class AccountStatusCriteria.
 *
 * @package namespace App\Criteria\Customer;
 */
class AccountStatusCriteria implements CriteriaInterface
{
    /**
     * Apply criteria in query repository
     *
     * @param string              $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {

        $id    = request()->get("id", null);
        $since = request()->get("since", null);
        $until = request()->get("until", null);

        if($since && $until){
            $model = $model->whereHas("credits", function($query) use ($since, $until){
                $query->whereHas("payments", function($query) use ($since, $until){
                    $query->whereBetween("credit_payments.created_at", [ "$since 00:00:00", "$until 23:59:59" ]);
                });
            });
        }

        if($id){
            $id    = explode(",", $id);
            $model = $model->whereIn("id", $id);
        }

        $model = $model->has("credits");

        return $model;
    }
}