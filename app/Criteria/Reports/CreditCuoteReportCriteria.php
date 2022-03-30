<?php

namespace App\Criteria\Reports;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class CreditCuoteReportCriteria.
 *
 * @package namespace App\Criteria\Reports;
 */
class CreditCuoteReportCriteria implements CriteriaInterface
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
        $furniture_id   = request()->get("furniture_id", null);
        $customer_id    = request()->get("customer_id", null);
        $since          = request()->get("since", null);
        $until          = request()->get("until", null);
        $status         = request()->get("status", null);

        if($furniture_id){
            $list = explode(",", $furniture_id);
            $model = $model->whereHas("credit", function($query) use ($list){
                $query->whereIn("furniture_id", $list);
            });
        }

        if($customer_id){
            $list = explode(",", $customer_id);
            $model = $model->whereHas("credit", function($query) use ($list){
                $query->whereHas("furniture", function($query) use ($list){
                    $query->whereIn("customer_id", $list);
                });
            });
        }

        if($since && $until){
            $model = $model->whereBetween("expiration_at", [ "$since 00:00:00", "$until 23:59:59" ]);
        }

        return $model;
    }
}
