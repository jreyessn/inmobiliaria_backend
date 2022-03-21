<?php

namespace App\Criteria\Sale;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class SaleCriteria.
 *
 * @package namespace App\Criteria\Sale;
 */
class SaleCriteria implements CriteriaInterface
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
        $customer_id = request()->get("customer_id", null);

        if($customer_id){
            $list = explode(",", $customer_id);
            $model = $model->whereIn("customer_id", $list);
        }

        return $model;
    }
}
