<?php

namespace App\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;


/**
 * Criterio para buscar en base a los usuarios gerentes
 */

/**
 * Class ManagerCriteriaCriteria.
 *
 * @package namespace App\Criteria;
 */
class ManagerCriteriaCriteria implements CriteriaInterface
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
        $filterWithUser = request()->get('manager_id', null);

        if($filterWithUser && $filterWithUser != 'undefined'){
            $model = $model->whereHas('user', function($query) use ($filterWithUser){

                $data = explode(',', $filterWithUser);

                $query->whereIn('user_id', $data);

            });

        }

        return $model;
    }
}
