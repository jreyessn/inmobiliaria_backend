<?php

namespace App\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class UserAssignedCriteria.
 *
 * @package namespace App\Criteria;
 */
class UserAssignedCriteria implements CriteriaInterface
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
        $users = request()->get("user_assigned_id", null);

        if($users){
            $list = explode(",", $users);

            $model = $model->whereIn("user_assigned_id", $list);
        }

        return $model;
    }
}
