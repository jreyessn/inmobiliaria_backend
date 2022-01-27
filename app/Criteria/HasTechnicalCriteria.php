<?php

namespace App\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class HasTechnicalCriteria.
 *
 * @package namespace App\Criteria;
 */
class HasTechnicalCriteria implements CriteriaInterface
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
        $user = request()->user();

        if($user && $user->hasRole(["Técnico"])){
            $model = $model->where("user_assigned_id", $user->id);
        }

        return $model;
    }
}
