<?php

namespace App\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class RoleCriteria.
 *
 * @package namespace App\Criteria;
 */
class RoleCriteria implements CriteriaInterface
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
        $roles = request()->get("role_id", null);
        $rolesNames = request()->get("role", null);

        if($roles){
            $rolesList = explode(",", $roles);

            $model = $model->whereHas("roles", function($query) use ($rolesList){
                $query->whereIn("id", $rolesList);
            });
        }

        if($rolesNames){
            $rolesList = explode(",", $rolesNames);
            
            $model = $model->whereHas("roles", function($query) use ($rolesList){
                $query->whereIn("name", $rolesList);
            });
        }

        return $model;
    }
}
