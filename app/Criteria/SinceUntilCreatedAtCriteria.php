<?php

namespace App\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class SinceUntilCreatedAtCriteria.
 *
 * @package namespace App\Criteria;
 */
class SinceUntilCreatedAtCriteria implements CriteriaInterface
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
        $since = request()->get("since", null);
        $until = request()->get("until", null);

        if($since && $until){
            $model = $model->whereBetween("created_at", [ "$since 00:00:00", "$until 23:59:59" ]);
        }

        return $model;
    }
}
