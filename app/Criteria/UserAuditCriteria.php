<?php

namespace App\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class UserAuditCriteria.
 *
 * @package namespace App\Criteria;
 */
class UserAuditCriteria implements CriteriaInterface
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

        $users = request()->get("user_id", null);

        if($users){
            $usersList = explode(",", $users);

            $model = $model->whereHas("user_created", function($query) use ($usersList){
                $query->whereIn("user_id", $usersList);
            });
        }

        return $model;
    }
}
