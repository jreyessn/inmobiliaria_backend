<?php

namespace App\Criteria\Sale;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class CreditCriteria.
 *
 * @package namespace App\Criteria\Sale;
 */
class CreditCriteria implements CriteriaInterface
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
        $status      = request()->get("status", 0);
        $currency_id = request()->get("currency_id", null);

        if($currency_id){
            $model = $model->whereHas("furniture", function($query) use ($currency_id){
                $query->where("currency_id", $currency_id);
            });
        }

        $model = $model->where("status", $status);

        return $model;
    }
}
