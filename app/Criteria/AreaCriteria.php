<?php

namespace App\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class AreaCriteria.
 *
 * @package namespace App\Criteria;
 */
class AreaCriteria implements CriteriaInterface
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
        $areas = request()->get("area_id", null);

        if($areas){
            $list = explode(",", $areas);

            $model = $model->whereHas("equipment", function($query) use ($list){
                $query->whereIn("id", $list);
            });
        }

        return $model;
    }
}
