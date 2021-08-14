<?php

namespace App\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class CustomerCriteria.
 *
 * @package namespace App\Criteria;
 */
class CustomerCriteria implements CriteriaInterface
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
            $model = $model->where("customer_id", $customer_id);
        }

        return $model;
    }
}
