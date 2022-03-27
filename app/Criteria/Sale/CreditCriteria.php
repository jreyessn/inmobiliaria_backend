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
        $status = request()->get("status", 0);

        $model = $model->where("status", $status);

        return $model;
    }
}
