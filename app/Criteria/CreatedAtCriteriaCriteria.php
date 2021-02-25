<?php

namespace App\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class CreatedAtCriteriaCriteria.
 *
 * @package namespace App\Criteria;
 */
class CreatedAtCriteriaCriteria implements CriteriaInterface
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
        $from = request()->get('from', null);
        $to = request()->get('to', null);

        if($from && $to){
            $model = $model->whereBetween('created_at', [ $from, $to ]);
        }

        return $model;
    }
}
