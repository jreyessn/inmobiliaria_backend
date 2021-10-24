<?php

namespace App\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class VisitUserCustomerCriteria.
 *
 * @package namespace App\Criteria;
 */
class VisitUserCustomerCriteria implements CriteriaInterface
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
        $visit_user_id = request()->get("visit_user_id", null);

        if($visit_user_id){
            $model = $model->whereHas("visits", function($q) use ($visit_user_id){
                $q->where("id", $visit_user_id);
            });
        }

        return $model;
    }
}
