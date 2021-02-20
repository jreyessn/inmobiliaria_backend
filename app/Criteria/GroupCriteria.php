<?php

namespace App\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class GroupCriteria.
 *
 * @package namespace App\Criteria;
 */
class GroupCriteria implements CriteriaInterface
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

        $groups = request()->get('group_id', null);

        if($groups){
            $explodes = explode(",", $groups);

            $model = $model->whereHas("groups", function($query) use ($explodes){
                $query->whereIn("groups_users.id", $explodes);
            });
        }
        

        return $model;
    }
}
