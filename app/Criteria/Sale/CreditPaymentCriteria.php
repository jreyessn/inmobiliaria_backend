<?php

namespace App\Criteria\Sale;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class CreditPaymentCriteria.
 *
 * @package namespace App\Criteria\Sale;
 */
class CreditPaymentCriteria implements CriteriaInterface
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
        $currency_id = request()->get("currency_id", null);

        if($currency_id){
            $model = $model->where("currency_id", $currency_id);
        }

        return $model;
    }
}
