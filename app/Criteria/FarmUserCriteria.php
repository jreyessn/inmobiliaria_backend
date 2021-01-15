<?php

namespace App\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class FarmUserCriteria.
 *
 * @package namespace App\Criteria;
 */
class FarmUserCriteria implements CriteriaInterface
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

        $filterWithUser = request()->get('only_user', null);

        if($filterWithUser){
            $model = $model->whereHas('user', function($query){
                $query->where('user_id', request()->user()->id);
            });
        }

        return $model;
    }
}
